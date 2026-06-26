@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="login-wrapper">
    <div class="login-card card">
        <div class="login-header">
            <div style="font-size:2.5rem; margin-bottom:.5rem;">📋</div>
            <h4 class="mb-0 fw-bold">Team Activity Tracker</h4>
            <p class="mb-0 mt-1" style="font-size:.82rem; opacity:.75;">Applications Support Team — Npontu Technologies</p>
        </div>
        <div class="card-body p-4">
            @if($errors->any())
            <div class="alert alert-danger py-2">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="you@npontu.com" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                    </div>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>
            <p class="text-center text-muted mt-3 mb-0" style="font-size:.78rem;">
                Default: admin@npontu.com / password
            </p>
        </div>
    </div>
</div>
@endsection
