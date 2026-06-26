<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from       = $request->from ? Carbon::parse($request->from)->startOfDay() : now()->startOfWeek();
        $to         = $request->to   ? Carbon::parse($request->to)->endOfDay()    : now()->endOfDay();
        $userId     = $request->user_id;
        $status     = $request->status;
        $activityId = $request->activity_id;

        // Build query for logs in the date range
        $query = ActivityLog::with(['activity', 'user'])
            ->whereBetween('logged_at', [$from, $to])
            ->orderByDesc('logged_at');

        if ($userId)     $query->where('user_id', $userId);
        if ($status)     $query->where('status', $status);
        if ($activityId) $query->where('activity_id', $activityId);

        $logs = $query->get();

        // Summary stats for the period
        $summary = [
            'total_updates'   => $logs->count(),
            'done_count'      => $logs->where('status', 'done')->count(),
            'pending_count'   => $logs->where('status', 'pending')->count(),
            'in_progress'     => $logs->where('status', 'in_progress')->count(),
            'unique_staff'    => $logs->pluck('user_id')->unique()->count(),
        ];

        // Group logs by date for timeline view
        $byDate = $logs->groupBy(fn($log) => $log->logged_at->format('Y-m-d'));

        $users      = User::orderBy('name')->get();
        $activities = Activity::orderBy('title')->get();

        return view('reports.index', compact(
            'logs', 'summary', 'byDate', 'users', 'activities',
            'from', 'to', 'userId', 'status', 'activityId'
        ));
    }
}
