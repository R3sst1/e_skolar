@extends('layouts.app')
@section('title', 'Generate Reports')

@section('header')
    <div class="intro-y flex items-center mt-8">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mr-3">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Dashboard
        </a>
        <h2 class="text-lg font-medium mr-auto">
            Generate Reports
        </h2>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6">
    <!-- BEGIN: Page Header -->
    <div class="col-span-12 mt-8">
        <div class="text-slate-500 mt-1">Export comprehensive reports in Excel, CSV, or PDF format</div>
    </div>
    <!-- END: Page Header -->

    <!-- BEGIN: Active Scholars Report -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Active Scholars Report</h2>
                <i data-lucide="user-check" class="w-5 h-5 text-primary"></i>
            </div>
            <div class="p-5">
                <p class="text-slate-600 mb-4">Generate a detailed report of all active scholars with filtering options.</p>
                <!-- Summary Table -->
                <div class="mb-4">
                    <h3 class="font-medium text-base mb-2">Summary</h3>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Active</td><td>{{ $scholarSummary['active'] ?? 0 }}</td></tr>
                            <tr><td>Graduated</td><td>{{ $scholarSummary['graduated'] ?? 0 }}</td></tr>
                            <tr><td>Discontinued</td><td>{{ $scholarSummary['discontinued'] ?? 0 }}</td></tr>
                            <tr><td>Masters Degree</td><td>{{ $scholarSummary['masters'] ?? 0 }}</td></tr>
                            <tr><td><strong>Total Scholars</strong></td><td><strong>{{ $scholarSummary['total'] ?? 0 }}</strong></td></tr>
                        </tbody>
                    </table>
                </div>
                <!-- End Summary Table -->
                <form action="{{ route('super-admin.reports.active-scholars') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select w-full">
                            <option value="">All Years</option>
                            @foreach(range(date('Y'), date('Y')-5) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Institution</label>
                        <select name="institution" class="form-select w-full">
                            <option value="">All Institutions</option>
                            @foreach(\App\Models\Institution::active()->orderBy('name')->get() as $institution)
                                <option value="{{ $institution->name }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Barangay</label>
                        <select name="barangay" class="form-select w-full">
                            <option value="">All Barangays</option>
                            @foreach(\App\Models\Barangay::orderBy('name')->get() as $barangay)
                                <option value="{{ $barangay->name }}">{{ $barangay->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Export Format</label>
                        <select name="format" class="form-select w-full">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-full">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Generate Active Scholars Report
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Active Scholars Report -->

    <!-- BEGIN: Disbursement Report -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Disbursement Report</h2>
                <i data-lucide="credit-card" class="w-5 h-5 text-success"></i>
            </div>
            <div class="p-5">
                <p class="text-slate-600 mb-4">Generate financial disbursement reports with detailed breakdowns.</p>
                <!-- Disbursement Summary Table -->
                <div class="mb-4">
                    <h3 class="font-medium text-base mb-2">Summary</h3>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disbursementSummary['by_category'] ?? [] as $category => $amount)
                                <tr><td>{{ $category }}</td><td>₱{{ number_format($amount, 2) }}</td></tr>
                            @endforeach
                            <tr><td><strong>Total Disbursed</strong></td><td><strong>₱{{ number_format($disbursementSummary['total'] ?? 0, 2) }}</strong></td></tr>
                        </tbody>
                    </table>
                </div>
                <!-- End Disbursement Summary Table -->
                <form action="{{ route('super-admin.reports.disbursement') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="form-label">School Year</label>
                        <select name="school_year" class="form-select w-full">
                            <option value="">All School Years</option>
                            @foreach(range(date('Y'), date('Y')-3) as $year)
                                <option value="{{ $year }}-{{ $year+1 }}">{{ $year }}-{{ $year+1 }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select w-full">
                            <option value="">All Semesters</option>
                            <option value="First">First Semester</option>
                            <option value="Second">Second Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select w-full">
                            <option value="">All Categories</option>
                            <option value="Tuition">Tuition</option>
                            <option value="Books">Books</option>
                            <option value="Living Allowance">Living Allowance</option>
                            <option value="Transportation">Transportation</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Institution</label>
                        <select name="institution" class="form-select w-full">
                            <option value="">All Institutions</option>
                            @foreach(\App\Models\Institution::active()->orderBy('name')->get() as $institution)
                                <option value="{{ $institution->name }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Export Format</label>
                        <select name="format" class="form-select w-full">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-full">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Generate Disbursement Report
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Disbursement Report -->

    <!-- BEGIN: Retention Report -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Scholar Retention Report</h2>
                <i data-lucide="trending-up" class="w-5 h-5 text-warning"></i>
            </div>
            <div class="p-5">
                <p class="text-slate-600 mb-4">Generate retention analysis reports with scholar status tracking.</p>
                
                <form action="{{ route('super-admin.reports.retention') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select w-full">
                            <option value="">All Years</option>
                            @foreach(range(date('Y'), date('Y')-5) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Institution</label>
                        <select name="institution" class="form-select w-full">
                            <option value="">All Institutions</option>
                            @foreach(\App\Models\Institution::active()->orderBy('name')->get() as $institution)
                                <option value="{{ $institution->name }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Export Format</label>
                        <select name="format" class="form-select w-full">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-full">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Generate Retention Report
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Retention Report -->

    <!-- BEGIN: Comprehensive Report -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Comprehensive Report</h2>
                <i data-lucide="bar-chart" class="w-5 h-5 text-pending"></i>
            </div>
            <div class="p-5">
                <p class="text-slate-600 mb-4">Generate a comprehensive multi-sheet report with all statistics and breakdowns.</p>
                
                <form action="{{ route('super-admin.reports.comprehensive') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="form-label">Year (Optional)</label>
                        <select name="year" class="form-select w-full">
                            <option value="">All Years</option>
                            @foreach(range(date('Y'), date('Y')-5) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Institution (Optional)</label>
                        <select name="institution" class="form-select w-full">
                            <option value="">All Institutions</option>
                            @foreach(\App\Models\Institution::active()->orderBy('name')->get() as $institution)
                                <option value="{{ $institution->name }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Export Format</label>
                        <select name="format" class="form-select w-full">
                            <option value="excel">Excel (.xlsx) - Multi-sheet</option>
                            <option value="csv">CSV (.csv) - Summary only</option>
                            <option value="pdf">PDF (.pdf) - Summary only</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-pending w-full">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Generate Comprehensive Report
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Comprehensive Report -->

    <!-- BEGIN: Report Information -->
    <div class="col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Report Information</h2>
                <i data-lucide="info" class="w-5 h-5 text-slate-500"></i>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium mb-2">Available Formats:</h3>
                        <ul class="text-slate-600 space-y-1">
                            <li>• <strong>Excel (.xlsx):</strong> Best for detailed analysis and data manipulation</li>
                            <li>• <strong>CSV (.csv):</strong> Compatible with most spreadsheet applications</li>
                            <li>• <strong>PDF (.pdf):</strong> Best for sharing and printing</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">Report Types:</h3>
                        <ul class="text-slate-600 space-y-1">
                            <li>• <strong>Active Scholars:</strong> Current active scholarship recipients</li>
                            <li>• <strong>Disbursement:</strong> Financial disbursement records and analysis</li>
                            <li>• <strong>Retention:</strong> Scholar retention and completion rates</li>
                            <li>• <strong>Comprehensive:</strong> Multi-sheet report with all statistics</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Report Information -->
</div>
@endsection 