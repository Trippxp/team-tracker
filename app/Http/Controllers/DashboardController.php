<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Main dashboard showing today's activity summary and recent updates.
     * Gives team leads a quick overview of what's done, pending, and in progress.
     */
    public function index()
    {
        $today = today();

        // Key statistics for today's shift
        $stats = [
            'total_activities' => Activity::count(),
            'done_today'       => ActivityLog::whereDate('logged_at', $today)
                                    ->where('status', 'done')
                                    ->distinct('activity_id')
                                    ->count('activity_id'),
            'pending_today'    => Activity::whereDoesntHave('todayLogs', fn($q) => $q->where('status', 'done'))->count(),
            'total_staff'      => User::where('role', 'staff')->count(),
        ];

        // All activities with latest log and today's update count
        $activities = Activity::with(['creator', 'latestLog.user', 'todayLogs.user'])
            ->orderByDesc('priority')
            ->orderBy('title')
            ->get();

        // Last 10 updates made today across all activities
        $recentUpdates = ActivityLog::with(['activity', 'user'])
            ->whereDate('logged_at', $today)
            ->orderByDesc('logged_at')
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'activities', 'recentUpdates'));
    }
}