<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * List all activities for a given date with all updates made that day.
     * Useful for shift handovers — shows who updated what and when.
     */
    public function index(Request $request)
    {
        $date = $request->date ? \Carbon\Carbon::parse($request->date) : today();

        $activities = Activity::with([
            'creator',
            'latestLog.user',
            'logs' => fn($q) => $q->whereDate('logged_at', $date)->with('user')->orderByDesc('logged_at'),
        ])->get();

        return view('activities.index', compact('activities', 'date'));
    }

    /**
     * Show form to create a new activity (admin only).
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('activities.create');
    }

    /**
     * Store a newly created activity.
     * Only admins may input new activities into the system.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|string|max:100',
            'priority'    => 'required|in:low,medium,high',
        ]);

        Activity::create(array_merge($data, ['created_by' => Auth::id()]));

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully.');
    }

    /**
     * Show a single activity with its full log history.
     */
    public function show(Activity $activity)
    {
        $activity->load(['creator', 'logs.user']);
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the status update form for an activity.
     * Any authenticated team member can log an update.
     */
    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    /**
     * Log a status update for an activity.
     * Captures the authenticated user's bio details and timestamp automatically.
     * A remark is required so handover notes are always meaningful.
     */
    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,done',
            'remark' => 'required|string|min:3|max:1000',
        ]);

        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id'     => Auth::id(),
            'status'      => $data['status'],
            'remark'      => $data['remark'],
            'logged_at'   => now(),
        ]);

        return redirect()->route('activities.index')
            ->with('success', "Activity \"{$activity->title}\" updated successfully.");
    }

    /**
     * Remove an activity from the system (admin only).
     */
    public function destroy(Activity $activity)
    {
        $this->authorizeAdmin();
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity removed.');
    }

    /**
     * Abort with 403 if the current user is not an admin.
     */
    private function authorizeAdmin(): void
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can perform this action.');
        }
    }
}