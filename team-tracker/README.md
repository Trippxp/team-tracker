# Team Activity Tracker
**Npontu Technologies — Platforms Developer Assignment**

A Laravel-based daily activity tracking system for the Applications Support Team.

---

## Features
- ✅ User authentication (login/logout, session-based)
- ✅ Admin can create activities; staff can log status updates
- ✅ Activity status: Pending / In Progress / Done
- ✅ Remarks per update, bio + timestamp captured automatically
- ✅ Daily view with full timeline of who updated what and when
- ✅ Date navigator (browse any past day)
- ✅ Reporting view with custom date range, staff, status, and activity filters
- ✅ Responsive UI built with Bootstrap 5

---

## Requirements
- PHP >= 8.1
- Composer
- MySQL (or MariaDB)
- A web server (Laravel's built-in `php artisan serve` works for development)

---

## Setup Instructions

### 1. Clone or copy the project
```bash
# If you put it on GitHub:
git clone https://github.com/your-username/team-tracker.git
cd team-tracker
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` and set your database credentials:
```
DB_DATABASE=team_tracker
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Create the database
In MySQL:
```sql
CREATE DATABASE team_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run migrations and seed
```bash
php artisan migrate --seed
```
This creates all tables and seeds:
- **Admin user:** `admin@npontu.com` / `password`
- **Staff user:** `kofi@npontu.com` / `password`
- 7 sample daily activities

### 6. Start the development server
```bash
php artisan serve
```
Visit: **http://localhost:8000**

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php       — Login / logout
│   │   ├── DashboardController.php  — Dashboard summary
│   │   ├── ActivityController.php   — CRUD + status updates
│   │   └── ReportController.php     — Date-range reporting
│   └── Middleware/
├── Models/
│   ├── User.php          — Staff / admin users
│   ├── Activity.php      — Activity definitions
│   └── ActivityLog.php   — Each status update (immutable log)

database/
├── migrations/
│   ├── ..._create_users_table.php
│   ├── ..._create_activities_table.php
│   └── ..._create_activity_logs_table.php
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── layouts/app.blade.php   — Master layout with sidebar
├── auth/login.blade.php
├── dashboard.blade.php
├── activities/
│   ├── index.blade.php     — Daily view with timeline per activity
│   ├── create.blade.php    — Admin: add activity
│   ├── edit.blade.php      — Staff: log status update
│   └── show.blade.php      — Full activity history
└── reports/index.blade.php — Custom-duration reporting

routes/web.php
```

---

## Design Decisions

| Decision | Rationale |
|---|---|
| Immutable `activity_logs` entries | Each update is a new log row — preserves full audit history |
| `latestLog` relation on Activity | Efficient single-query to get current status |
| Soft deletes on Activity | Admin can "remove" an activity without losing its historical logs |
| Role-based access (`admin` / `staff`) | Admins manage activities; staff only log updates |
| Timezone set to `Africa/Accra` | Correct local time for Ghanaian team |

---

## Default Credentials
| Role | Email | Password |
|---|---|---|
| Admin | admin@npontu.com | password |
| Staff | kofi@npontu.com | password |
