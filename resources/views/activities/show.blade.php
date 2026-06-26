@extends('layouts.app')
@section('title', 'Activity History')
@section('page-title', 'Activity History')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('activities.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            <h5 class="fw-bold mb-0">{{ $activity->title }}</h5>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size:.75rem; text-transform:uppercase;">Description</div>
                        <p class="mb-0">{{ $activity->description ?? '—' }}</p>
                    </div>
                    <div class="col-md-2">
                        <div class="text-muted" style="font-size:.75rem; text-transform:uppercase;">Category</div>
                        <strong>{{ $activity->category }}</strong>
                    </div>
                    <div class="col-md-2">
                        <div class="text-muted" style="font-size:.75rem; text-transform:uppercase;">Priority</div>
                        <strong class="priority-{{ $activity->priority }}">{{ ucfirst($activity->priority) }}</strong>
                    </div>
                    <div class="col-md-2">
                        <div class="text-muted" style="font-size:.75rem; text-transform:uppercase;">Created By</div>
                        <strong>{{ $activity->creator->name }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2" style="color:var(--npontu-accent);"></i>
                    Full Update History ({{ $activity->logs->count() }} entries)
                </h6>
            </div>
            <div class="card-body">
                @forelse($activity->logs->groupBy(fn($l) => $l->logged_at->format('Y-m-d')) as $date => $dayLogs)
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span style="background:var(--npontu-primary); color:#fff; border-radius:6px; padding:.2rem .75rem; font-size:.78rem; font-weight:600;">
                            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
                        </span>
                        <span class="text-muted" style="font-size:.78rem;">{{ $dayLogs->count() }} update(s)</span>
                    </div>
                    <div class="timeline">
                        @foreach($dayLogs as $log)
                        @php $badgeMap = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress']; @endphp
                        <div class="timeline-item">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <span class="badge rounded-pill {{ $badgeMap[$log->status] ?? '' }}">
                                    {{ str_replace('_',' ', ucfirst($log->status)) }}
                                </span>
                                <strong style="font-size:.88rem;">{{ $log->user->name }}</strong>
                                <span class="text-muted" style="font-size:.76rem;">{{ $log->user->department }}</span>
                                <span class="ms-auto text-muted" style="font-size:.76rem;">{{ $log->logged_at->format('H:i:s') }}</span>
                            </div>
                            @if($log->remark)
                            <div style="background:#f8f9fa; border-left:3px solid var(--npontu-accent); padding:.5rem .75rem; border-radius:0 6px 6px 0; font-size:.82rem; color:#555;">
                                {{ $log->remark }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <p class="text-center text-muted py-4">No updates logged yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
