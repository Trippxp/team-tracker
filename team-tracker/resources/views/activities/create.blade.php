@extends('layouts.app')
@section('title', 'Add Activity')
@section('page-title', 'Add New Activity')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2" style="color:var(--npontu-accent);"></i>New Activity</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('activities.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Activity Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="e.g. Daily SMS Count vs Log Count" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Describe what this activity involves...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
                                   list="categories" value="{{ old('category', 'Monitoring') }}" required>
                            <datalist id="categories">
                                <option value="Monitoring">
                                <option value="Maintenance">
                                <option value="Support">
                                <option value="Reporting">
                                <option value="General">
                            </datalist>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-save me-2"></i>Save Activity
                        </button>
                        <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
