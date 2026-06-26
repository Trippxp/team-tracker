<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * List all activities (daily view with all today's updates).
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
     * Show a single activity with all its logs.
     */
    public function show(Activity $activity)
    {
        $activity->load(['creator', 'logs.user']);
        return view('activities.show', compact('activity'));
    }

    /**
     * Show form to update status/remark for an activity.
     */
    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update the activity status — creates a new ActivityLog entry.
     */
    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,done',
            'remark' => 'nullable|string|max:1000',
        ]);

        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id'     => Auth::id(),
            'status'      => $data['status'],
            'remark'      => $data['remark'] ?? null,
            'logged_at'   => now(),
        ]);

        return redirect()->route('activities.index')
            ->with('success', "Activity \"{$activity->title}\" updated successfully.");
    }

    /**
     * Soft-delete an activity (admin only).
     */
    public function destroy(Activity $activity)
    {
        $this->authorizeAdmin();
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity removed.');
    }

    private function authorizeAdmin(): void
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can perform this action.');
        }
    }
}
