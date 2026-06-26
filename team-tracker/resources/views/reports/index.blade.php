@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Activity Reports')

@section('content')

{{-- ── Filter form ── --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-funnel me-2" style="color:var(--npontu-accent);"></i>Filter Report</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">

            <div class="col-sm-6 col-md-3">
                <label class="form-label fw-semibold">From Date</label>
                <input type="date" name="from" class="form-control" value="{{ $from->format('Y-m-d') }}">
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label fw-semibold">To Date</label>
                <input type="date" name="to" class="form-control" value="{{ $to->format('Y-m-d') }}">
            </div>
            <div class="col-sm-6 col-md-2">
                <label class="form-label fw-semibold">Staff Member</label>
                <select name="user_id" class="form-select">
                    <option value="">All Staff</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending"     {{ $status === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="done"        {{ $status === 'done'        ? 'selected' : '' }}>Done</option>
                </select>
            </div>
            <div class="col-sm-6 col-md-2">
                <label class="form-label fw-semibold">Activity</label>
                <select name="activity_id" class="form-select">
                    <option value="">All Activities</option>
                    @foreach($activities as $act)
                    <option value="{{ $act->id }}" {{ $activityId == $act->id ? 'selected' : '' }}>{{ Str::limit($act->title, 30) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-semibold">
                    <i class="bi bi-search me-2"></i>Run Report
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- ── Summary Stats ── --}}
<div class="row g-3 mb-4">
    @foreach([
        ['Total Updates', $summary['total_updates'], 'bi-bar-chart', '#dbeafe', '#1e40af'],
        ['Done',          $summary['done_count'],     'bi-check-circle', '#d1fae5', '#065f46'],
        ['Pending',       $summary['pending_count'],  'bi-hourglass-split', '#fff3cd', '#856404'],
        ['In Progress',   $summary['in_progress'],    'bi-arrow-repeat', '#e0e7ff', '#3730a3'],
        ['Staff Active',  $summary['unique_staff'],   'bi-people', '#f3e8ff', '#6b21a8'],
    ] as [$label, $value, $icon, $bg, $color])
    <div class="col-6 col-md">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-icon" style="background:{{ $bg }};">
                    <i class="bi {{ $icon }}" style="color:{{ $color }};"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.72rem;">{{ $label }}</div>
                    <div class="fw-bold fs-5">{{ $value }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Results ── --}}
@if($byDate->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-inbox" style="font-size:2.5rem;"></i>
        <p class="mt-2">No records found for the selected filters.</p>
    </div>
</div>
@else

{{-- Group by date --}}
@foreach($byDate as $date => $dayLogs)
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white border-0 py-2 d-flex align-items-center justify-content-between">
        <span style="font-weight:700; color:var(--npontu-primary);">
            <i class="bi bi-calendar me-2"></i>
            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
        </span>
        <span class="badge bg-secondary rounded-pill">{{ $dayLogs->count() }} update(s)</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.85rem;">
            <thead class="table-light">
                <tr>
                    <th>Time</th>
                    <th>Activity</th>
                    <th>Status</th>
                    <th>Staff Name</th>
                    <th>Department</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
            @foreach($dayLogs as $log)
            @php $badgeMap = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress']; @endphp
            <tr>
                <td class="text-muted">{{ $log->logged_at->format('H:i:s') }}</td>
                <td>
                    <strong>{{ $log->activity->title }}</strong><br>
                    <small class="text-muted">{{ $log->activity->category }}</small>
                </td>
                <td>
                    <span class="badge rounded-pill {{ $badgeMap[$log->status] ?? '' }}">
                        {{ str_replace('_',' ', ucfirst($log->status)) }}
                    </span>
                </td>
                <td>{{ $log->user->name }}</td>
                <td class="text-muted">{{ $log->user->department ?? '—' }}</td>
                <td style="max-width:220px; white-space:normal;">{{ $log->remark ?? '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endif

@endsection
