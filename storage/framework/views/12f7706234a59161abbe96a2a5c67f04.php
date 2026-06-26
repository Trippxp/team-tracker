<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('page-title', 'Activity Reports'); ?>

<?php $__env->startSection('content'); ?>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-funnel me-2" style="color:var(--npontu-accent);"></i>Filter Report</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('reports.index')); ?>" class="row g-3 align-items-end">

            <div class="col-sm-6 col-md-3">
                <label class="form-label fw-semibold">From Date</label>
                <input type="date" name="from" class="form-control" value="<?php echo e($from->format('Y-m-d')); ?>">
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label fw-semibold">To Date</label>
                <input type="date" name="to" class="form-control" value="<?php echo e($to->format('Y-m-d')); ?>">
            </div>
            <div class="col-sm-6 col-md-2">
                <label class="form-label fw-semibold">Staff Member</label>
                <select name="user_id" class="form-select">
                    <option value="">All Staff</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>" <?php echo e($userId == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-sm-6 col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending"     <?php echo e($status === 'pending'     ? 'selected' : ''); ?>>Pending</option>
                    <option value="in_progress" <?php echo e($status === 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                    <option value="done"        <?php echo e($status === 'done'        ? 'selected' : ''); ?>>Done</option>
                </select>
            </div>
            <div class="col-sm-6 col-md-2">
                <label class="form-label fw-semibold">Activity</label>
                <select name="activity_id" class="form-select">
                    <option value="">All Activities</option>
                    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($act->id); ?>" <?php echo e($activityId == $act->id ? 'selected' : ''); ?>><?php echo e(Str::limit($act->title, 30)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-semibold">
                    <i class="bi bi-search me-2"></i>Run Report
                </button>
                <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>


<div class="row g-3 mb-4">
    <?php $__currentLoopData = [
        ['Total Updates', $summary['total_updates'], 'bi-bar-chart', '#dbeafe', '#1e40af'],
        ['Done',          $summary['done_count'],     'bi-check-circle', '#d1fae5', '#065f46'],
        ['Pending',       $summary['pending_count'],  'bi-hourglass-split', '#fff3cd', '#856404'],
        ['In Progress',   $summary['in_progress'],    'bi-arrow-repeat', '#e0e7ff', '#3730a3'],
        ['Staff Active',  $summary['unique_staff'],   'bi-people', '#f3e8ff', '#6b21a8'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $icon, $bg, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-md">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-icon" style="background:<?php echo e($bg); ?>;">
                    <i class="bi <?php echo e($icon); ?>" style="color:<?php echo e($color); ?>;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.72rem;"><?php echo e($label); ?></div>
                    <div class="fw-bold fs-5"><?php echo e($value); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php if($byDate->isEmpty()): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-inbox" style="font-size:2.5rem;"></i>
        <p class="mt-2">No records found for the selected filters.</p>
    </div>
</div>
<?php else: ?>


<?php $__currentLoopData = $byDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $dayLogs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white border-0 py-2 d-flex align-items-center justify-content-between">
        <span style="font-weight:700; color:var(--npontu-primary);">
            <i class="bi bi-calendar me-2"></i>
            <?php echo e(\Carbon\Carbon::parse($date)->format('l, d M Y')); ?>

        </span>
        <span class="badge bg-secondary rounded-pill"><?php echo e($dayLogs->count()); ?> update(s)</span>
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
            <?php $__currentLoopData = $dayLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $badgeMap = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress']; ?>
            <tr>
                <td class="text-muted"><?php echo e($log->logged_at->format('H:i:s')); ?></td>
                <td>
                    <strong><?php echo e($log->activity->title); ?></strong><br>
                    <small class="text-muted"><?php echo e($log->activity->category); ?></small>
                </td>
                <td>
                    <span class="badge rounded-pill <?php echo e($badgeMap[$log->status] ?? ''); ?>">
                        <?php echo e(str_replace('_',' ', ucfirst($log->status))); ?>

                    </span>
                </td>
                <td><?php echo e($log->user->name); ?></td>
                <td class="text-muted"><?php echo e($log->user->department ?? '—'); ?></td>
                <td style="max-width:220px; white-space:normal;"><?php echo e($log->remark ?? '—'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CYRIL\Desktop\team-tracker\resources\views/reports/index.blade.php ENDPATH**/ ?>