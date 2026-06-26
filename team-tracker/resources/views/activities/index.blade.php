@extends('layouts.app')
@section('title', 'Daily Activities')
@section('page-title', 'Daily Activities — ' . $date->format('l, d M Y'))

@section('content')

{{-- ── Date navigator ── --}}
<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('activities.index', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
           class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>

        <form method="GET" action="{{ route('activities.index') }}" class="d-flex align-items-center gap-2">
            <input type="date" name="date" class="form-control form-control-sm"
                   value="{{ $date->format('Y-m-d') }}" onchange="this.form.submit()">
        </form>

        <a href="{{ route('activities.index', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
           class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>

        <a href="{{ route('activities.index') }}" class="btn btn-sm btn-outline-primary">Today</a>
    </div>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('activities.create') }}" class="btn btn-accent ms-auto">
        <i class="bi bi-plus-circle me-1"></i>New Activity
    </a>
    @endif
</div>

{{-- ── Legend ── --}}
<div class="d-flex gap-3 mb-3 flex-wrap">
    <span><span class="badge badge-done rounded-pill">Done</span></span>
    <span><span class="badge badge-progress rounded-pill">In Progress</span></span>
    <span><span class="badge badge-pending rounded-pill">Pending</span></span>
</div>

{{-- ── Activity cards ── --}}
@forelse($activities as $activity)
@php
    $latest = $activity->latestLog;
    $todayLogs = $activity->logs; // already filtered by date in controller
    $badgeMap = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress'];
    $currentBadge = $badgeMap[$latest?->status ?? 'pending'] ?? 'badge-pending';
@endphp
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row align-items-start">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-1">
                    {{-- Priority indicator --}}
                    <span class="priority-{{ $activity->priority }}" title="{{ ucfirst($activity->priority) }} priority">
                        <i class="bi bi-{{ $activity->priority === 'high' ? 'exclamation-circle-fill' : ($activity->priority === 'medium' ? 'dash-circle-fill' : 'circle') }}"></i>
                    </span>
                    <h6 class="mb-0 fw-bold">{{ $activity->title }}</h6>
                    <span class="badge badge-{{ $activity->priority === 'high' ? 'danger' : ($activity->priority === 'medium' ? 'warning' : 'secondary') }} rounded-pill ms-1" style="font-size:.68rem;">{{ ucfirst($activity->priority) }}</span>
                </div>
                @if($activity->description)
                <p class="text-muted mb-1" style="font-size:.82rem;">{{ $activity->description }}</p>
                @endif
                <small class="text-muted"><i class="bi bi-tag me-1"></i>{{ $activity->category }}</small>
            </div>

            <div class="col-md-3 mt-2 mt-md-0">
                {{-- Current status --}}
                <div style="font-size:.75rem; color:#888; text-transform:uppercase; letter-spacing:.05em;">Current Status</div>
                <span class="badge rounded-pill {{ $currentBadge }} mt-1">
                    {{ str_replace('_',' ', ucfirst($latest?->status ?? 'pending')) }}
                </span>
                @if($latest)
                <div class="text-muted mt-1" style="font-size:.76rem;">
                    by {{ $latest->user->name }} at {{ $latest->logged_at->format('H:i') }}
                </div>
                @endif
            </div>

            <div class="col-md-3 mt-2 mt-md-0 text-md-end">
                <a href="{{ route('activities.edit', $activity) }}" class="btn btn-sm btn-accent me-1">
                    <i class="bi bi-pencil me-1"></i>Update
                </a>
                <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-clock-history"></i>
                </a>
                @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('activities.destroy', $activity) }}" class="d-inline"
                      onsubmit="return confirm('Delete this activity?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash"></i></button>
                </form>
                @endif
            </div>
        </div>

        {{-- ── Today's update timeline for this activity ── --}}
        @if($todayLogs->count())
        <hr class="my-2">
        <div style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:#888; margin-bottom:.5rem;">
            Updates on {{ $date->format('d M Y') }} ({{ $todayLogs->count() }})
        </div>
        <div class="timeline">
            @foreach($todayLogs as $log)
            <div class="timeline-item">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="badge rounded-pill {{ $badgeMap[$log->status] ?? '' }}">
                        {{ str_replace('_',' ', ucfirst($log->status)) }}
                    </span>
                    <span style="font-size:.82rem; font-weight:600;">{{ $log->user->name }}</span>
                    <span style="font-size:.76rem; color:#888;">{{ $log->user->department }}</span>
                    <span class="ms-auto" style="font-size:.76rem; color:#aaa;">{{ $log->logged_at->format('H:i:s') }}</span>
                </div>
                @if($log->remark)
                <div class="text-muted mt-1" style="font-size:.82rem; background:#f8f9fa; border-radius:6px; padding:.4rem .7rem;">
                    <i class="bi bi-chat-left-text me-1"></i>{{ $log->remark }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="mt-2" style="font-size:.8rem; color:#bbb;"><i class="bi bi-dash-circle me-1"></i>No updates recorded for this date.</div>
        @endif
    </div>
</div>
@empty
<div class="text-center py-5 text-muted">
    <i class="bi bi-inbox" style="font-size:3rem;"></i>
    <p class="mt-2">No activities found.</p>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('activities.create') }}" class="btn btn-accent">Add First Activity</a>
    @endif
</div>
@endforelse
@endsection
