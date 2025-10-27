<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Scholar;
use App\Models\DisbursementBatch;
use App\Models\DisbursementBatchStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        // Get counts for different user types
        $stats = [
            'total_applicants' => User::where('role', 'applicant')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_users' => User::count(),
            'active_scholars' => Scholar::where('status', 'active')->count(),
        ];

        // Get recent activities
        $recentScholars = Scholar::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get quick stats for charts
        $scholarsByStatus = Scholar::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        return view('dashboardcontent.super-admin.dashboard', compact(
            'stats',
            'recentScholars',
            'scholarsByStatus'
        ));
    }

    public function analyticsOverview()
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        // Get comprehensive analytics data
        $totalScholars = Scholar::count();
        $activeScholars = Scholar::where('status', 'active')->count();
        $graduatedScholars = Scholar::where('status', 'graduated')->count();
        $discontinuedScholars = Scholar::where('status', 'discontinued')->count();
        
        $totalApplications = \App\Models\Application::count();
        $pendingApplications = \App\Models\Application::where('status', 'pending')->count();
        $approvedApplications = \App\Models\Application::where('status', 'approved')->count();
        $rejectedApplications = \App\Models\Application::where('status', 'rejected')->count();
        
        $totalDisbursements = DisbursementBatch::sum('total_amount') ?? 0;
        $totalBatches = DisbursementBatch::count();
        $disbursedBatches = DisbursementBatch::where('status', 'disbursed')->count();
        
        // Calculate retention rate
        $retentionRate = $totalScholars > 0 
            ? (($activeScholars + $graduatedScholars) / $totalScholars) * 100 
            : 0;
        
        // Get institution breakdown
        $institutionStats = Scholar::select('institution')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active')
            ->selectRaw('SUM(CASE WHEN status = "graduated" THEN 1 ELSE 0 END) as graduated')
            ->groupBy('institution')
            ->get();
        
        // Get recent activity
        $recentApplications = \App\Models\Application::with('user')->latest()->take(5)->get();
        $recentDisbursements = DisbursementBatch::latest()->take(5)->get();

        return view('dashboardcontent.analytics-overview', compact(
            'totalScholars', 'activeScholars', 'graduatedScholars', 'discontinuedScholars',
            'totalApplications', 'pendingApplications', 'approvedApplications', 'rejectedApplications',
            'totalDisbursements', 'totalBatches', 'disbursedBatches', 'retentionRate',
            'institutionStats', 'recentApplications', 'recentDisbursements'
        ));
    }

    public function scholarStats(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        // Base query
        $query = Scholar::query();

        // Apply filters
        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('institution')) {
            $query->where('institution', $request->institution);
        }
        if ($request->filled('barangay')) {
            $query->where('barangay', $request->barangay);
        }

        // Get basic statistics
        $totalScholars = $query->count();
        $activeScholars = $query->clone()->where('status', 'active')->count();
        $graduatedScholars = $query->clone()->where('status', 'graduated')->count();
        
        // Calculate retention rate
        $totalPastScholars = $query->clone()->whereIn('status', ['graduated', 'discontinued'])->count();
        $retentionRate = $totalPastScholars > 0 
            ? ($graduatedScholars / $totalPastScholars) * 100 
            : 100;

        // Get institution statistics
        $institutionStats = $query->clone()
            ->select('institution')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active')
            ->selectRaw('SUM(CASE WHEN status = "graduated" THEN 1 ELSE 0 END) as graduated')
            ->selectRaw('(SUM(CASE WHEN status = "graduated" THEN 1 ELSE 0 END) * 100.0 / COUNT(*)) as retention_rate')
            ->groupBy('institution')
            ->get();

        // Get barangay statistics
        $barangayStats = $query->clone()
            ->select('barangay')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('barangay')
            ->get();

        // Prepare chart data
        $institutionLabels = $institutionStats->pluck('institution');
        $institutionData = $institutionStats->pluck('total');
        $barangayLabels = $barangayStats->pluck('barangay');
        $barangayData = $barangayStats->pluck('total');

        // Get available years for filter
        $years = Scholar::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get available institutions for filter
        $institutions = \App\Models\Institution::active()
            ->orderBy('name')
            ->pluck('name');

        // Get available barangays for filter
        $barangays = Scholar::select('barangay')
            ->distinct()
            ->orderBy('barangay')
            ->pluck('barangay');

        if ($request->ajax()) {
            return response()->json([
                'stats' => [
                    'totalScholars' => $totalScholars,
                    'activeScholars' => $activeScholars,
                    'graduatedScholars' => $graduatedScholars,
                    'retentionRate' => number_format($retentionRate, 1),
                ],
                'charts' => [
                    'institution' => [
                        'labels' => $institutionLabels,
                        'data' => $institutionData,
                    ],
                    'barangay' => [
                        'labels' => $barangayLabels,
                        'data' => $barangayData,
                    ],
                ],
                'table' => [
                    'institutionStats' => $institutionStats,
                ],
            ]);
        }

        return view('dashboardcontent.super-admin.scholar-stats', compact(
            'totalScholars',
            'activeScholars',
            'graduatedScholars',
            'retentionRate',
            'institutionStats',
            'institutionLabels',
            'institutionData',
            'barangayLabels',
            'barangayData',
            'years',
            'institutions',
            'barangays'
        ));
    }

    public function disbursementStats(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        // Get filter parameters
        $status = $request->input('status');

        // Base query
        $query = DisbursementBatch::query();

        // Apply filters
        if ($status) {
            $query->where('status', $status);
        }

        // Get basic statistics
        $totalBatches = DisbursementBatch::count();
        $pendingBatches = DisbursementBatch::where('status', 'pending')->count();
        $reviewedBatches = DisbursementBatch::where('status', 'reviewed')->count();
        $disbursedBatches = DisbursementBatch::where('status', 'disbursed')->count();
        $totalAmount = DisbursementBatch::sum('total_amount');
        $totalStudents = DisbursementBatchStudent::count();

        // Get status statistics
        $statusStats = DisbursementBatch::select('status')
            ->selectRaw('COUNT(*) as total_batches')
            ->selectRaw('SUM(total_amount) as total_amount')
            ->groupBy('status')
            ->get();

        // Prepare status chart data
        $statusLabels = $statusStats->pluck('status');
        $statusData = $statusStats->pluck('total_batches');

        // Get monthly statistics
        $monthlyStats = DisbursementBatch::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->selectRaw('COUNT(*) as total_batches')
            ->selectRaw('SUM(total_amount) as total_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyLabels = $monthlyStats->pluck('month');
        $monthlyData = $monthlyStats->pluck('total_amount');

        if ($request->ajax()) {
            return response()->json([
                'stats' => [
                    'totalBatches' => $totalBatches,
                    'pendingBatches' => $pendingBatches,
                    'reviewedBatches' => $reviewedBatches,
                    'disbursedBatches' => $disbursedBatches,
                    'totalAmount' => number_format($totalAmount, 2),
                    'totalStudents' => $totalStudents,
                ],
                'charts' => [
                    'status' => [
                        'labels' => $statusLabels,
                        'data' => $statusData,
                    ],
                    'monthly' => [
                        'labels' => $monthlyLabels,
                        'data' => $monthlyData,
                    ],
                ],
                'table' => [
                    'statusStats' => $statusStats,
                ],
            ]);
        }

        return view('dashboardcontent.super-admin.disbursement-stats', compact(
            'totalBatches',
            'pendingBatches',
            'reviewedBatches',
            'disbursedBatches',
            'totalAmount',
            'totalStudents',
            'statusStats',
            'statusLabels',
            'statusData',
            'monthlyLabels',
            'monthlyData'
        ));
    }

    public function generateDisbursementReport(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $format = $request->get('format', 'excel');
        $status = $request->get('status');

        // Build query
        $query = DisbursementBatch::with('disbursementBatchStudents.application.user');

        if ($status) {
            $query->where('status', $status);
        }

        $disbursementBatches = $query->orderBy('created_at', 'desc')->get();

        $data = [];
        foreach ($disbursementBatches as $batch) {
            foreach ($batch->disbursementBatchStudents as $student) {
                $data[] = [
                    'Batch Reference' => $batch->reference_number,
                    'Student Name' => $student->application->user->first_name . ' ' . $student->application->user->last_name,
                    'Scholarship' => $student->application->scholarship->name ?? 'N/A',
                    'Amount' => $student->application->scholarship->per_scholar_amount ? '₱' . number_format($student->application->scholarship->per_scholar_amount, 2) : 'N/A',
                    'Status' => ucfirst($student->status),
                    'Batch Status' => ucfirst($batch->status),
                    'Created Date' => \Carbon\Carbon::parse($batch->created_at)->format('M d, Y'),
                    'Remarks' => $batch->remarks ?? 'N/A',
                ];
            }
        }

        $filename = 'disbursement_report_' . now()->format('Y-m-d_H-i-s');

        return $this->exportData($data, $filename, $format, 'Disbursement Report');
    }

    public function retentionStats(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        // Get filter parameters
        $year = $request->input('year');
        $institution = $request->input('institution');

        // Base query
        $query = Scholar::query();

        // Apply filters
        if ($year) {
            $query->whereYear('start_date', $year);
        }
        if ($institution) {
            $query->where('institution', $institution);
        }

        // Calculate overall statistics
        $totalScholars = $query->count();
        $activeScholars = $query->clone()->where('status', 'active')->count();
        $graduatedScholars = $query->clone()->where('status', 'graduated')->count();
        $discontinuedScholars = $query->clone()->where('status', 'discontinued')->count();

        // Calculate rates
        $overallRetentionRate = $totalScholars > 0 
            ? (($activeScholars + $graduatedScholars) / $totalScholars) * 100 
            : 0;

        $renewalRate = $activeScholars > 0 
            ? ($activeScholars / ($activeScholars + $discontinuedScholars)) * 100 
            : 0;

        $graduationRate = $totalScholars > 0 
            ? ($graduatedScholars / $totalScholars) * 100 
            : 0;

        $dropoutRate = $totalScholars > 0 
            ? ($discontinuedScholars / $totalScholars) * 100 
            : 0;

        // Get institution statistics
        $institutionStats = $query->select('institution')
            ->selectRaw('COUNT(*) as total_scholars')
            ->selectRaw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_scholars')
            ->selectRaw('SUM(CASE WHEN status = "graduated" THEN 1 ELSE 0 END) as graduated_scholars')
            ->selectRaw('SUM(CASE WHEN status = "discontinued" THEN 1 ELSE 0 END) as discontinued_scholars')
            ->selectRaw('((SUM(CASE WHEN status IN ("active", "graduated") THEN 1 ELSE 0 END) * 100.0) / COUNT(*)) as retention_rate')
            ->groupBy('institution')
            ->get();

        // Calculate trends
        foreach ($institutionStats as $stat) {
            $previousYearRate = Scholar::where('institution', $stat->institution)
                ->whereYear('start_date', '<', now()->year)
                ->selectRaw('((SUM(CASE WHEN status IN ("active", "graduated") THEN 1 ELSE 0 END) * 100.0) / COUNT(*)) as rate')
                ->first()
                ->rate ?? 0;

            $stat->trend = $stat->retention_rate - $previousYearRate;
        }

        // Get available years and institutions for filters
        $years = Scholar::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $institutions = \App\Models\Institution::active()
            ->orderBy('name')
            ->pluck('name');

        // Prepare chart data
        $trendLabels = $years;
        $trendData = $years->map(function($year) {
            return Scholar::whereYear('start_date', $year)
                ->selectRaw('((SUM(CASE WHEN status IN ("active", "graduated") THEN 1 ELSE 0 END) * 100.0) / COUNT(*)) as rate')
                ->first()
                ->rate ?? 0;
        });

        $institutionLabels = $institutionStats->pluck('institution');
        $institutionData = $institutionStats->pluck('retention_rate');

        if ($request->ajax()) {
            return response()->json([
                'stats' => [
                    'overallRetentionRate' => number_format($overallRetentionRate, 1),
                    'renewalRate' => number_format($renewalRate, 1),
                    'graduationRate' => number_format($graduationRate, 1),
                    'dropoutRate' => number_format($dropoutRate, 1),
                ],
                'charts' => [
                    'trend' => [
                        'labels' => $trendLabels,
                        'data' => $trendData,
                    ],
                    'institution' => [
                        'labels' => $institutionLabels,
                        'data' => $institutionData,
                    ],
                ],
                'table' => [
                    'institutionStats' => $institutionStats,
                ],
            ]);
        }

        return view('dashboardcontent.super-admin.retention-stats', compact(
            'overallRetentionRate',
            'renewalRate',
            'graduationRate',
            'dropoutRate',
            'institutionStats',
            'trendLabels',
            'trendData',
            'institutionLabels',
            'institutionData',
            'years',
            'institutions'
        ));
    }

    // ==================== REPORT GENERATION METHODS ====================

    public function generateActiveScholarsReport(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $format = $request->get('format', 'excel');
        $year = $request->get('year');
        $institution = $request->get('institution');
        $barangay = $request->get('barangay');

        // Build query
        $query = Scholar::where('status', 'active');

        if ($year) {
            $query->whereYear('start_date', $year);
        }
        if ($institution) {
            $query->where('institution', $institution);
        }
        if ($barangay) {
            $query->where('barangay', $barangay);
        }

        $scholars = $query->join('users', 'scholars.user_id', '=', 'users.id')
            ->orderBy('scholars.institution')
            ->orderBy('users.last_name')
            ->select('scholars.*', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.email', 'users.phone_number')
            ->get();

        $data = [];
        foreach ($scholars as $scholar) {
            $data[] = [
                'Scholar ID' => $scholar->id,
                'Full Name' => $scholar->first_name . ' ' . ($scholar->middle_name ? $scholar->middle_name . ' ' : '') . $scholar->last_name,
                'Institution' => $scholar->institution,
                'Course' => $scholar->course,
                'Year Level' => $scholar->year_level,
                'Category' => $scholar->category,
                'Barangay' => $scholar->barangay,
                'Start Date' => \Carbon\Carbon::parse($scholar->start_date)->format('M d, Y'),
                'Email' => $scholar->email ?? 'N/A',
                'Phone' => $scholar->phone_number ?? 'N/A',
            ];
        }

        $filename = 'active_scholars_report_' . now()->format('Y-m-d_H-i-s');

        return $this->exportData($data, $filename, $format, 'Active Scholars Report');
    }

    public function generateRetentionReport(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $format = $request->get('format', 'excel');
        $year = $request->get('year');
        $institution = $request->get('institution');

        // Build query
        $query = Scholar::with('user');

        if ($year) {
            $query->whereYear('start_date', $year);
        }
        if ($institution) {
            $query->where('institution', $institution);
        }

        $scholars = $query->orderBy('institution')->orderBy('status')->get();

        $data = [];
        foreach ($scholars as $scholar) {
            $data[] = [
                'Scholar ID' => $scholar->id,
                'Full Name' => $scholar->user->first_name . ' ' . $scholar->user->middle_name . ' ' . $scholar->user->last_name,
                'Institution' => $scholar->institution,
                'Course' => $scholar->course,
                'Year Level' => $scholar->year_level,
                'Status' => ucfirst($scholar->status),
                'Category' => $scholar->category,
                'Barangay' => $scholar->barangay,
                'Start Date' => \Carbon\Carbon::parse($scholar->start_date)->format('M d, Y'),
                'End Date' => $scholar->end_date ? \Carbon\Carbon::parse($scholar->end_date)->format('M d, Y') : 'N/A',
                'Duration (Months)' => $scholar->end_date ? \Carbon\Carbon::parse($scholar->start_date)->diffInMonths(\Carbon\Carbon::parse($scholar->end_date)) : \Carbon\Carbon::parse($scholar->start_date)->diffInMonths(now()),
            ];
        }

        $filename = 'retention_report_' . now()->format('Y-m-d_H-i-s');

        return $this->exportData($data, $filename, $format, 'Scholar Retention Report');
    }

    public function generateComprehensiveReport(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $format = $request->get('format', 'excel');
        $year = $request->get('year');
        $institution = $request->get('institution');

        // Get summary statistics
        $totalScholars = Scholar::count();
        $activeScholars = Scholar::where('status', 'active')->count();
        $graduatedScholars = Scholar::where('status', 'graduated')->count();
        $discontinuedScholars = Scholar::where('status', 'discontinued')->count();

        // Get institution breakdown
        $institutionStats = Scholar::select('institution')
            ->selectRaw('COUNT(*) as total_scholars')
            ->selectRaw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_scholars')
            ->selectRaw('SUM(CASE WHEN status = "graduated" THEN 1 ELSE 0 END) as graduated_scholars')
            ->selectRaw('SUM(CASE WHEN status = "discontinued" THEN 1 ELSE 0 END) as discontinued_scholars')
            ->selectRaw('((SUM(CASE WHEN status IN ("active", "graduated") THEN 1 ELSE 0 END) * 100.0) / COUNT(*)) as retention_rate')
            ->groupBy('institution')
            ->get();


        $data = [
            // Summary Sheet
            'Summary' => [
                ['Metric', 'Value'],
                ['Total Scholars', $totalScholars],
                ['Active Scholars', $activeScholars],
                ['Graduated Scholars', $graduatedScholars],
                ['Discontinued Scholars', $discontinuedScholars],
                ['Retention Rate', number_format(($activeScholars + $graduatedScholars) / $totalScholars * 100, 1) . '%'],
            ],
            // Institution Breakdown
            'Institution Breakdown' => [
                ['Institution', 'Total Scholars', 'Active', 'Graduated', 'Discontinued', 'Retention Rate (%)'],
                ...$institutionStats->map(function($stat) {
                    return [
                        $stat->institution,
                        $stat->total_scholars,
                        $stat->active_scholars,
                        $stat->graduated_scholars,
                        $stat->discontinued_scholars,
                        number_format($stat->retention_rate, 1)
                    ];
                })
            ]
        ];

        $filename = 'comprehensive_report_' . now()->format('Y-m-d_H-i-s');

        return $this->exportMultiSheetData($data, $filename, $format, 'Comprehensive Scholarship Report');
    }

    public function reports(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        // Scholar summary
        $scholarSummary = [
            'active' => \App\Models\Scholar::where('status', 'active')->count(),
            'graduated' => \App\Models\Scholar::where('status', 'graduated')->count(),
            'discontinued' => \App\Models\Scholar::where('status', 'discontinued')->count(),
            'masters' => \App\Models\Scholar::where('category', 'Master Degree')->count(),
            'total' => \App\Models\Scholar::count(),
        ];

        return view('dashboardcontent.super-admin.reports', compact('scholarSummary'));
    }

    private function exportData($data, $filename, $format, $title)
    {
        if ($format === 'csv') {
            return $this->exportToCsv($data, $filename, $title);
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($data, $filename, $title);
        } else {
            return $this->exportToExcel($data, $filename, $title);
        }
    }

    private function exportToExcel($data, $filename, $title)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:' . chr(65 + count($data[0]) - 1) . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        // Set headers
        $headers = array_keys($data[0]);
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $col++;
        }

        // Set data
        $row = 4;
        foreach ($data as $rowData) {
            $col = 'A';
            foreach ($rowData as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        // Auto-size columns
        foreach (range('A', chr(65 + count($headers) - 1)) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/reports/' . $filename . '.xlsx');
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $writer->save($path);

        return Response::download($path, $filename . '.xlsx')->deleteFileAfterSend();
    }

    private function exportToCsv($data, $filename, $title)
    {
        $headers = array_keys($data[0]);
        
        $output = fopen('php://temp', 'w+');
        
        // Add title
        fputcsv($output, [$title]);
        fputcsv($output, []); // Empty row
        
        // Add headers
        fputcsv($output, $headers);
        
        // Add data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ]);
    }

    private function exportToPdf($data, $filename, $title)
    {
        // For PDF export, we'll create a simple HTML table and convert it
        $html = '<html><head><style>';
        $html .= 'body { font-family: Arial, sans-serif; margin: 20px; }';
        $html .= 'h1 { color: #333; text-align: center; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 20px; }';
        $html .= 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
        $html .= 'th { background-color: #f2f2f2; font-weight: bold; }';
        $html .= 'tr:nth-child(even) { background-color: #f9f9f9; }';
        $html .= '</style></head><body>';
        
        $html .= '<h1>' . $title . '</h1>';
        $html .= '<p>Generated on: ' . now()->format('F d, Y \a\t h:i A') . '</p>';
        
        $html .= '<table>';
        
        // Headers
        $headers = array_keys($data[0]);
        $html .= '<tr>';
        foreach ($headers as $header) {
            $html .= '<th>' . $header . '</th>';
        }
        $html .= '</tr>';
        
        // Data
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $value) {
                $html .= '<td>' . $value . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</table></body></html>';

        // For now, return HTML (you can integrate with a PDF library like Dompdf)
        return Response::make($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.html"',
        ]);
    }

    private function exportMultiSheetData($data, $filename, $format, $title)
    {
        if ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            
            foreach ($data as $sheetName => $sheetData) {
                if ($spreadsheet->getActiveSheet()->getTitle() === 'Worksheet') {
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setTitle($sheetName);
                } else {
                    $sheet = $spreadsheet->createSheet();
                    $sheet->setTitle($sheetName);
                }

                // Set data
                $row = 1;
                foreach ($sheetData as $rowData) {
                    $col = 'A';
                    foreach ($rowData as $value) {
                        $sheet->setCellValue($col . $row, $value);
                        if ($row === 1) {
                            $sheet->getStyle($col . $row)->getFont()->setBold(true);
                        }
                        $col++;
                    }
                    $row++;
                }

                // Auto-size columns
                foreach (range('A', chr(65 + count($sheetData[0]) - 1)) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }

            $writer = new Xlsx($spreadsheet);
            $path = storage_path('app/public/reports/' . $filename . '.xlsx');
            
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            $writer->save($path);
            return Response::download($path, $filename . '.xlsx')->deleteFileAfterSend();
        }

        // For other formats, export the first sheet
        return $this->exportData($data[array_key_first($data)], $filename, $format, $title);
    }
} 