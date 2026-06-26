@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard — ' . now()->format('l, d M Y'))

@section('content')

{{-- ── Stat cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#dbeafe;">
                    <i class="bi bi-list-check" style="color:#1e40af;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.78rem;">Total Activities</div>
                    <div class="fw-bold fs-4">{{ $stats['total_activities'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#d1fae5;">
                    <i class="bi bi-check-circle" style="color:#065f46;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.78rem;">Done Today</div>
                    <div class="fw-bold fs-4">{{ $stats['done_today'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff3cd;">
                    <i class="bi bi-hourglass-split" style="color:#856404;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.78rem;">Pending Today</div>
                    <div class="fw-bold fs-4">{{ $stats['pending_today'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#f3e8ff;">
                    <i class="bi bi-people" style="color:#6b21a8;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.78rem;">Support Staff</div>
                    <div class="fw-bold fs-4">{{ $stats['total_staff'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- ── Today's activity summary table ── --}}
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2" style="color:var(--npontu-accent);"></i>Today's Activities</h6>
                <a href="{{ route('activities.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 activity-row">
                    <thead class="table-light">
                        <tr>
                            <th>Activity</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Last Updated By</th>
                            <th>Time</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($activities as $activity)
                        @php $latest = $activity->latestLog; @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:.88rem;">{{ $activity->title }}</div>
                                <small class="text-muted">{{ $activity->category }}</small>
                            </td>
                            <td>
                                <i class="bi bi-circle-fill priority-{{ $activity->priority }}" style="font-size:.5rem;"></i>
                                {{ ucfirst($activity->priority) }}
                            </td>
                            <td>
                                @if($latest)
                                    @php $badge = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress'][$latest->status] ?? 'bg-secondary text-white'; @endphp
                                    <span class="badge rounded-pill {{ $badge }}">{{ str_replace('_',' ', ucfirst($latest->status)) }}</span>
                                @else
                                    <span class="badge rounded-pill badge-pending">Pending</span>
                                @endif
                            </td>
                            <td>{{ $latest?->user?->name ?? '—' }}</td>
                            <td style="font-size:.78rem; color:#888;">{{ $latest?->logged_at->format('H:i') ?? '—' }}</td>
                            <td>
                                <a href="{{ route('activities.edit', $activity) }}" class="btn btn-sm btn-accent">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No activities found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Recent updates timeline ── --}}
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-activity me-2" style="color:var(--npontu-accent);"></i>Recent Updates Today</h6>
            </div>
            <div class="card-body" style="overflow-y:auto; max-height:420px;">
                @forelse($recentUpdates as $log)
                <div class="d-flex gap-2 mb-3">
                    <div class="avatar flex-shrink-0" style="width:30px; height:30px; border-radius:50%; background:var(--npontu-primary); color:#fff; display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:700;">
                        {{ strtoupper(substr($log->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:.82rem; font-weight:600;">{{ $log->activity->title }}</div>
                        <div style="font-size:.76rem; color:#888;">
                            {{ $log->user->name }} &middot; {{ $log->logged_at->format('H:i') }}
                        </div>
                        @php $badge = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress'][$log->status] ?? ''; @endphp
                        <span class="badge rounded-pill {{ $badge }} mt-1">{{ str_replace('_',' ', ucfirst($log->status)) }}</span>
                        @if($log->remark)
                        <div class="text-muted mt-1" style="font-size:.76rem;">{{ Str::limit($log->remark, 60) }}</div>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center text-muted py-4" style="font-size:.85rem;">No updates today yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
