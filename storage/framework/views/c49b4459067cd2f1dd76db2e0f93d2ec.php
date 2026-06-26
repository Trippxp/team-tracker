<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard — ' . now()->format('l, d M Y')); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#dbeafe;">
                    <i class="bi bi-list-check" style="color:#1e40af;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.78rem;">Total Activities</div>
                    <div class="fw-bold fs-4"><?php echo e($stats['total_activities']); ?></div>
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
                    <div class="fw-bold fs-4"><?php echo e($stats['done_today']); ?></div>
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
                    <div class="fw-bold fs-4"><?php echo e($stats['pending_today']); ?></div>
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
                    <div class="fw-bold fs-4"><?php echo e($stats['total_staff']); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2" style="color:var(--npontu-accent);"></i>Today's Activities</h6>
                <a href="<?php echo e(route('activities.index')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
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
                    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $latest = $activity->latestLog; ?>
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:.88rem;"><?php echo e($activity->title); ?></div>
                                <small class="text-muted"><?php echo e($activity->category); ?></small>
                            </td>
                            <td>
                                <i class="bi bi-circle-fill priority-<?php echo e($activity->priority); ?>" style="font-size:.5rem;"></i>
                                <?php echo e(ucfirst($activity->priority)); ?>

                            </td>
                            <td>
                                <?php if($latest): ?>
                                    <?php $badge = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress'][$latest->status] ?? 'bg-secondary text-white'; ?>
                                    <span class="badge rounded-pill <?php echo e($badge); ?>"><?php echo e(str_replace('_',' ', ucfirst($latest->status))); ?></span>
                                <?php else: ?>
                                    <span class="badge rounded-pill badge-pending">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($latest?->user?->name ?? '—'); ?></td>
                            <td style="font-size:.78rem; color:#888;"><?php echo e($latest?->logged_at->format('H:i') ?? '—'); ?></td>
                            <td>
                                <a href="<?php echo e(route('activities.edit', $activity)); ?>" class="btn btn-sm btn-accent">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No activities found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-activity me-2" style="color:var(--npontu-accent);"></i>Recent Updates Today</h6>
            </div>
            <div class="card-body" style="overflow-y:auto; max-height:420px;">
                <?php $__empty_1 = true; $__currentLoopData = $recentUpdates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex gap-2 mb-3">
                    <div class="avatar flex-shrink-0" style="width:30px; height:30px; border-radius:50%; background:var(--npontu-primary); color:#fff; display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:700;">
                        <?php echo e(strtoupper(substr($log->user->name, 0, 1))); ?>

                    </div>
                    <div>
                        <div style="font-size:.82rem; font-weight:600;"><?php echo e($log->activity->title); ?></div>
                        <div style="font-size:.76rem; color:#888;">
                            <?php echo e($log->user->name); ?> &middot; <?php echo e($log->logged_at->format('H:i')); ?>

                        </div>
                        <?php $badge = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress'][$log->status] ?? ''; ?>
                        <span class="badge rounded-pill <?php echo e($badge); ?> mt-1"><?php echo e(str_replace('_',' ', ucfirst($log->status))); ?></span>
                        <?php if($log->remark): ?>
                        <div class="text-muted mt-1" style="font-size:.76rem;"><?php echo e(Str::limit($log->remark, 60)); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-muted py-4" style="font-size:.85rem;">No updates today yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CYRIL\Desktop\team-tracker\resources\views/dashboard.blade.php ENDPATH**/ ?>