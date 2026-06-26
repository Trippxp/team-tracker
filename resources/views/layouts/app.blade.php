<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Team Activity Tracker') — Npontu Technologies</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --npontu-primary:   #1a3c5e;
            --npontu-accent:    #e8820c;
            --npontu-light:     #f4f7fb;
            --npontu-done:      #198754;
            --npontu-pending:   #fd7e14;
            --npontu-progress:  #0d6efd;
        }

        body { background: var(--npontu-light); font-family: 'Segoe UI', sans-serif; }

        /* ── Sidebar ── */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--npontu-primary);
            position: fixed;
            top: 0; left: 0;
            display: flex; flex-direction: column;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .sidebar-brand img { height: 36px; }
        .sidebar-brand .app-name {
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            line-height: 1.2;
            margin-top: .35rem;
        }
        .sidebar-brand .app-sub {
            color: rgba(255,255,255,.55);
            font-size: .72rem;
        }
        .sidebar-nav { flex: 1; padding: 1rem 0; }
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,.75);
            padding: .65rem 1.5rem;
            border-radius: 0;
            display: flex; align-items: center; gap: .75rem;
            font-size: .88rem;
            transition: all .2s;
        }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
            border-left: 3px solid var(--npontu-accent);
            padding-left: calc(1.5rem - 3px);
        }
        .sidebar-nav .nav-link i { font-size: 1.1rem; min-width: 1.25rem; }
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.12);
            font-size: .78rem; color: rgba(255,255,255,.5);
        }

        /* ── Main ── */
        .main-wrapper { margin-left: 250px; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 99;
        }
        .topbar .page-title { font-weight: 600; color: var(--npontu-primary); font-size: 1.1rem; }
        .topbar .user-badge {
            display: flex; align-items: center; gap: .5rem;
            font-size: .85rem; color: #555;
        }
        .topbar .user-badge .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--npontu-primary);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .85rem;
        }

        .content { padding: 2rem; }

        /* ── Cards ── */
        .stat-card {
            border: none; border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* ── Status badges ── */
        .badge-done      { background: #d1fae5; color: #065f46; }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-progress  { background: #dbeafe; color: #1e40af; }

        /* ── Priority pips ── */
        .priority-high   { color: #dc3545; }
        .priority-medium { color: #fd7e14; }
        .priority-low    { color: #6c757d; }

        /* ── Timeline ── */
        .timeline { position: relative; padding-left: 1.5rem; }
        .timeline::before {
            content: ''; position: absolute;
            left: 6px; top: 0; bottom: 0;
            width: 2px; background: #e2e8f0;
        }
        .timeline-item { position: relative; margin-bottom: 1.25rem; }
        .timeline-item::before {
            content: ''; position: absolute;
            left: -1.2rem; top: .35rem;
            width: 10px; height: 10px;
            border-radius: 50%; background: var(--npontu-accent);
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--npontu-accent);
        }

        /* ── Table ── */
        .table-hover tbody tr:hover { background: #f0f4ff; }
        .activity-row td { vertical-align: middle; }

        /* ── Login page ── */
        .login-wrapper {
            min-height: 100vh; background: var(--npontu-light);
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            width: 420px;
            border: none; border-radius: 16px;
            box-shadow: 0 8px 32px rgba(26,60,94,.12);
        }
        .login-header {
            background: var(--npontu-primary);
            border-radius: 16px 16px 0 0;
            padding: 2rem; text-align: center; color: #fff;
        }
        .btn-primary   { background: var(--npontu-primary); border-color: var(--npontu-primary); }
        .btn-primary:hover { background: #14304e; border-color: #14304e; }
        .btn-accent    { background: var(--npontu-accent); border-color: var(--npontu-accent); color: #fff; }
        .btn-accent:hover { background: #c96e0a; border-color: #c96e0a; color: #fff; }

        /* scrollable table containers */
        .table-responsive { border-radius: 10px; overflow: hidden; }
    </style>

    @stack('styles')
</head>
<body>
@auth
<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="app-name">Team Activity Tracker</div>
        <div class="app-sub">Npontu Technologies</div>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
            <i class="bi bi-list-task"></i> Daily Activities
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('activities.create') }}" class="nav-link {{ request()->routeIs('activities.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Add Activity
        </a>
        @endif
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>
    </nav>
    <div class="sidebar-footer">
        &copy; {{ date('Y') }} Npontu Technologies
    </div>
</aside>

<!-- Main wrapper -->
<div class="main-wrapper">
    <!-- Topbar -->
    <header class="topbar">
        <span class="page-title">@yield('page-title', 'Dashboard')</span>
        <div class="user-badge">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div style="font-weight:600;">{{ auth()->user()->name }}</div>
                <div style="font-size:.75rem; color:#999;">{{ ucfirst(auth()->user()->role) }} &middot; {{ auth()->user()->department }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ms-2">
                @csrf
                <button class="btn btn-sm btn-outline-secondary" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Content -->
    <main class="content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>
</div>
@else
    @yield('content')
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
