<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grievance;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total' => Grievance::count(),
            'pending' => Grievance::where('status', 'pending')->count(),
            'under_investigation' => Grievance::where('status', 'under_investigation')->count(),
            'resolved' => Grievance::where('status', 'resolved')->count(),
            'closed' => Grievance::where('status', 'closed')->count(),
        ];

        // Recent grievances
        $recentGrievances = Grievance::with(['category', 'attachments'])
            ->latest('submitted_at')
            ->take(10)
            ->get();

        // Grievances by category
        $grievancesByCategory = Category::withCount('grievances')
            ->having('grievances_count', '>', 0)
            ->get();

        // Grievances by status (for chart)
        $statusData = Grievance::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Monthly trend (last 6 months)
        $monthlyTrend = Grievance::select(
                DB::raw('DATE_FORMAT(submitted_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('submitted_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentGrievances',
            'grievancesByCategory',
            'statusData',
            'monthlyTrend'
        ));
    }
}