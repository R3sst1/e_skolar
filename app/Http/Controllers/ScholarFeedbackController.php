<?php

namespace App\Http\Controllers;

use App\Models\ScholarFeedback;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScholarFeedbackController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isScholar()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $scholar = Scholar::where('user_id', Auth::id())->first();
        if (!$scholar) {
            return redirect()->route('dashboard')->with('error', 'You are not a registered scholar.');
        }

        $feedbacks = ScholarFeedback::where('scholar_id', $scholar->id)
            ->latest()
            ->paginate(10);

        return view('feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        if (!Auth::user()->isScholar()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $scholar = Scholar::where('user_id', Auth::id())->first();
        if (!$scholar) {
            return redirect()->route('dashboard')->with('error', 'You are not a registered scholar.');
        }

        return view('feedback.create', compact('scholar'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isScholar()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $scholar = Scholar::where('user_id', Auth::id())->first();
        if (!$scholar) {
            return redirect()->route('dashboard')->with('error', 'You are not a registered scholar.');
        }

        $request->validate([
            'category' => 'required|in:academic,support,financial,general',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'anonymous' => 'boolean',
        ]);

        ScholarFeedback::create([
            'scholar_id' => $scholar->id,
            'category' => $request->category,
            'rating' => $request->rating,
            'title' => $request->title,
            'message' => $request->message,
            'anonymous' => $request->has('anonymous'),
            'status' => 'submitted',
        ]);

        return redirect()->route('feedback.index')->with('success', 'Feedback submitted successfully.');
    }

    public function show(ScholarFeedback $feedback)
    {
        if (!Auth::user()->isScholar()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $scholar = Scholar::where('user_id', Auth::id())->first();
        if (!$scholar || $feedback->scholar_id !== $scholar->id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        return view('feedback.show', compact('feedback'));
    }

    // Admin methods
    public function adminIndex(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = ScholarFeedback::with(['scholar.user']);

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        $feedbacks = $query->latest()->paginate(15);

        $stats = [
            'total_feedback' => ScholarFeedback::count(),
            'average_rating' => ScholarFeedback::avg('rating'),
            'pending_review' => ScholarFeedback::where('status', 'submitted')->count(),
            'reviewed' => ScholarFeedback::where('status', 'reviewed')->count(),
        ];

        return view('feedback.admin-index', compact('feedbacks', 'stats'));
    }

    public function adminShow(ScholarFeedback $feedback)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        return view('feedback.admin-show', compact('feedback'));
    }

    public function updateStatus(Request $request, ScholarFeedback $feedback)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:submitted,reviewed,resolved',
            'admin_response' => 'nullable|string|max:1000',
        ]);

        $feedback->update([
            'status' => $request->status,
            'admin_response' => $request->admin_response,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Feedback status updated successfully.');
    }

    public function analytics(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = ScholarFeedback::query();

        if ($request->year) {
            $query->whereYear('created_at', $request->year);
        }

        $totalFeedback = $query->count();
        $averageRating = $query->avg('rating');
        $categoryStats = $query->select('category')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('AVG(rating) as avg_rating')
            ->groupBy('category')
            ->get();

        $ratingDistribution = $query->select('rating')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        $monthlyTrends = $query->selectRaw('MONTH(created_at) as month')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('AVG(rating) as avg_rating')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('feedback.analytics', compact(
            'totalFeedback',
            'averageRating',
            'categoryStats',
            'ratingDistribution',
            'monthlyTrends'
        ));
    }
}
