@extends('layouts.app')
@section('title', 'Update Activity')
@section('page-title', 'Update Activity Status')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Activity info card --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid var(--npontu-accent) !important;">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div style="font-size:2rem; line-height:1;">📋</div>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $activity->title }}</h5>
                        <p class="text-muted mb-1" style="font-size:.85rem;">{{ $activity->description }}</p>
                        <div class="d-flex gap-3 flex-wrap" style="font-size:.78rem; color:#888;">
                            <span><i class="bi bi-tag me-1"></i>{{ $activity->category }}</span>
                            <span class="priority-{{ $activity->priority }}">
                                <i class="bi bi-circle-fill" style="font-size:.5rem;"></i> {{ ucfirst($activity->priority) }} Priority
                            </span>
                            <span><i class="bi bi-person me-1"></i>Created by {{ $activity->creator->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Update form --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2" style="color:var(--npontu-accent);"></i>Log Status Update</h6>
                <small class="text-muted">Updating as: <strong>{{ auth()->user()->name }}</strong> — {{ now()->format('d M Y, H:i') }}</small>
            </div>
            <div class="card-body p-4">

                {{-- Personnel Bio Info (read-only, captured automatically) --}}
                <div class="alert alert-light border mb-4" style="font-size:.82rem;">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <i class="bi bi-person-badge me-1"></i>
                            <strong>Name:</strong> {{ auth()->user()->name }}
                        </div>
                        <div class="col-sm-6">
                            <i class="bi bi-building me-1"></i>
                            <strong>Department:</strong> {{ auth()->user()->department ?? 'N/A' }}
                        </div>
                        <div class="col-sm-6">
                            <i class="bi bi-envelope me-1"></i>
                            <strong>Email:</strong> {{ auth()->user()->email }}
                        </div>
                        <div class="col-sm-6">
                            <i class="bi bi-clock me-1"></i>
                            <strong>Timestamp:</strong> {{ now()->format('d M Y, H:i:s') }}
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('activities.update', $activity) }}">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach(['pending' => ['⏳','badge-pending','Pending'], 'in_progress' => ['🔄','badge-progress','In Progress'], 'done' => ['✅','badge-done','Done']] as $value => [$icon, $cls, $label])
                            <label class="status-option flex-fill" style="cursor:pointer;">
                                <input type="radio" name="status" value="{{ $value }}" class="d-none status-radio"
                                    {{ ($activity->latestLog?->status ?? 'pending') === $value ? 'checked' : '' }}>
                                <div class="card text-center py-3 status-card border-2 {{ ($activity->latestLog?->status ?? 'pending') === $value ? 'border-primary bg-light' : '' }}"
                                     style="border-radius:10px; transition:all .15s;">
                                    <div style="font-size:1.5rem;">{{ $icon }}</div>
                                    <div class="fw-semibold mt-1" style="font-size:.85rem;">{{ $label }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('status')<div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Remark / Notes</label>
                        <textarea name="remark" class="form-control @error('remark') is-invalid @enderror"
                                  rows="4" placeholder="Add any relevant notes, discrepancies found, or handover information...">{{ old('remark') }}</textarea>
                        @error('remark')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-save me-2"></i>Save Update
                        </button>
                        <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Previous logs for this activity today --}}
        @if($activity->todayLogs->count())
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Today's Logs</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                @foreach($activity->todayLogs as $log)
                    @php $badgeMap = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress']; @endphp
                    <div class="timeline-item">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge rounded-pill {{ $badgeMap[$log->status] ?? '' }}">
                                {{ str_replace('_',' ', ucfirst($log->status)) }}
                            </span>
                            <strong style="font-size:.85rem;">{{ $log->user->name }}</strong>
                            <span style="font-size:.76rem; color:#aaa;">{{ $log->logged_at->format('H:i:s') }}</span>
                        </div>
                        @if($log->remark)
                        <div class="text-muted mt-1" style="font-size:.82rem;">{{ $log->remark }}</div>
                        @endif
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Highlight selected status card
    document.querySelectorAll('.status-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.status-card').forEach(c => {
                c.classList.remove('border-primary','bg-light');
            });
            this.closest('.status-option').querySelector('.status-card')
                .classList.add('border-primary','bg-light');
        });
    });
</script>
@endpush
@endsection
