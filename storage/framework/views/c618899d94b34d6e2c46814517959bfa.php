<?php $__env->startSection('title', 'Daily Activities'); ?>
<?php $__env->startSection('page-title', 'Daily Activities — ' . $date->format('l, d M Y')); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo e(route('activities.index', ['date' => $date->copy()->subDay()->format('Y-m-d')])); ?>"
           class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>

        <form method="GET" action="<?php echo e(route('activities.index')); ?>" class="d-flex align-items-center gap-2">
            <input type="date" name="date" class="form-control form-control-sm"
                   value="<?php echo e($date->format('Y-m-d')); ?>" onchange="this.form.submit()">
        </form>

        <a href="<?php echo e(route('activities.index', ['date' => $date->copy()->addDay()->format('Y-m-d')])); ?>"
           class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>

        <a href="<?php echo e(route('activities.index')); ?>" class="btn btn-sm btn-outline-primary">Today</a>
    </div>

    <?php if(auth()->user()->isAdmin()): ?>
    <a href="<?php echo e(route('activities.create')); ?>" class="btn btn-accent ms-auto">
        <i class="bi bi-plus-circle me-1"></i>New Activity
    </a>
    <?php endif; ?>
</div>


<div class="d-flex gap-3 mb-3 flex-wrap">
    <span><span class="badge badge-done rounded-pill">Done</span></span>
    <span><span class="badge badge-progress rounded-pill">In Progress</span></span>
    <span><span class="badge badge-pending rounded-pill">Pending</span></span>
</div>


<?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php
    $latest = $activity->latestLog;
    $todayLogs = $activity->logs; // already filtered by date in controller
    $badgeMap = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress'];
    $currentBadge = $badgeMap[$latest?->status ?? 'pending'] ?? 'badge-pending';
?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row align-items-start">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-1">
                    
                    <span class="priority-<?php echo e($activity->priority); ?>" title="<?php echo e(ucfirst($activity->priority)); ?> priority">
                        <i class="bi bi-<?php echo e($activity->priority === 'high' ? 'exclamation-circle-fill' : ($activity->priority === 'medium' ? 'dash-circle-fill' : 'circle')); ?>"></i>
                    </span>
                    <h6 class="mb-0 fw-bold"><?php echo e($activity->title); ?></h6>
                    <span class="badge badge-<?php echo e($activity->priority === 'high' ? 'danger' : ($activity->priority === 'medium' ? 'warning' : 'secondary')); ?> rounded-pill ms-1" style="font-size:.68rem;"><?php echo e(ucfirst($activity->priority)); ?></span>
                </div>
                <?php if($activity->description): ?>
                <p class="text-muted mb-1" style="font-size:.82rem;"><?php echo e($activity->description); ?></p>
                <?php endif; ?>
                <small class="text-muted"><i class="bi bi-tag me-1"></i><?php echo e($activity->category); ?></small>
            </div>

            <div class="col-md-3 mt-2 mt-md-0">
                
                <div style="font-size:.75rem; color:#888; text-transform:uppercase; letter-spacing:.05em;">Current Status</div>
                <span class="badge rounded-pill <?php echo e($currentBadge); ?> mt-1">
                    <?php echo e(str_replace('_',' ', ucfirst($latest?->status ?? 'pending'))); ?>

                </span>
                <?php if($latest): ?>
                <div class="text-muted mt-1" style="font-size:.76rem;">
                    by <?php echo e($latest->user->name); ?> at <?php echo e($latest->logged_at->format('H:i')); ?>

                </div>
                <?php endif; ?>
            </div>

            <div class="col-md-3 mt-2 mt-md-0 text-md-end">
                <a href="<?php echo e(route('activities.edit', $activity)); ?>" class="btn btn-sm btn-accent me-1">
                    <i class="bi bi-pencil me-1"></i>Update
                </a>
                <a href="<?php echo e(route('activities.show', $activity)); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-clock-history"></i>
                </a>
                <?php if(auth()->user()->isAdmin()): ?>
                <form method="POST" action="<?php echo e(route('activities.destroy', $activity)); ?>" class="d-inline"
                      onsubmit="return confirm('Delete this activity?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash"></i></button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($todayLogs->count()): ?>
        <hr class="my-2">
        <div style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:#888; margin-bottom:.5rem;">
            Updates on <?php echo e($date->format('d M Y')); ?> (<?php echo e($todayLogs->count()); ?>)
        </div>
        <div class="timeline">
            <?php $__currentLoopData = $todayLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="timeline-item">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="badge rounded-pill <?php echo e($badgeMap[$log->status] ?? ''); ?>">
                        <?php echo e(str_replace('_',' ', ucfirst($log->status))); ?>

                    </span>
                    <span style="font-size:.82rem; font-weight:600;"><?php echo e($log->user->name); ?></span>
                    <span style="font-size:.76rem; color:#888;"><?php echo e($log->user->department); ?></span>
                    <span class="ms-auto" style="font-size:.76rem; color:#aaa;"><?php echo e($log->logged_at->format('H:i:s')); ?></span>
                </div>
                <?php if($log->remark): ?>
                <div class="text-muted mt-1" style="font-size:.82rem; background:#f8f9fa; border-radius:6px; padding:.4rem .7rem;">
                    <i class="bi bi-chat-left-text me-1"></i><?php echo e($log->remark); ?>

                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="mt-2" style="font-size:.8rem; color:#bbb;"><i class="bi bi-dash-circle me-1"></i>No updates recorded for this date.</div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="text-center py-5 text-muted">
    <i class="bi bi-inbox" style="font-size:3rem;"></i>
    <p class="mt-2">No activities found.</p>
    <?php if(auth()->user()->isAdmin()): ?>
    <a href="<?php echo e(route('activities.create')); ?>" class="btn btn-accent">Add First Activity</a>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CYRIL\Desktop\team-tracker\resources\views/activities/index.blade.php ENDPATH**/ ?>