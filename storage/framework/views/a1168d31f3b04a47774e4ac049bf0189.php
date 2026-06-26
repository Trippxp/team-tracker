<?php $__env->startSection('title', 'Update Activity'); ?>
<?php $__env->startSection('page-title', 'Update Activity Status'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-7">

        
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid var(--npontu-accent) !important;">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div style="font-size:2rem; line-height:1;">📋</div>
                    <div>
                        <h5 class="fw-bold mb-1"><?php echo e($activity->title); ?></h5>
                        <p class="text-muted mb-1" style="font-size:.85rem;"><?php echo e($activity->description); ?></p>
                        <div class="d-flex gap-3 flex-wrap" style="font-size:.78rem; color:#888;">
                            <span><i class="bi bi-tag me-1"></i><?php echo e($activity->category); ?></span>
                            <span class="priority-<?php echo e($activity->priority); ?>">
                                <i class="bi bi-circle-fill" style="font-size:.5rem;"></i> <?php echo e(ucfirst($activity->priority)); ?> Priority
                            </span>
                            <span><i class="bi bi-person me-1"></i>Created by <?php echo e($activity->creator->name); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2" style="color:var(--npontu-accent);"></i>Log Status Update</h6>
                <small class="text-muted">Updating as: <strong><?php echo e(auth()->user()->name); ?></strong> — <?php echo e(now()->format('d M Y, H:i')); ?></small>
            </div>
            <div class="card-body p-4">

                
                <div class="alert alert-light border mb-4" style="font-size:.82rem;">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <i class="bi bi-person-badge me-1"></i>
                            <strong>Name:</strong> <?php echo e(auth()->user()->name); ?>

                        </div>
                        <div class="col-sm-6">
                            <i class="bi bi-building me-1"></i>
                            <strong>Department:</strong> <?php echo e(auth()->user()->department ?? 'N/A'); ?>

                        </div>
                        <div class="col-sm-6">
                            <i class="bi bi-envelope me-1"></i>
                            <strong>Email:</strong> <?php echo e(auth()->user()->email); ?>

                        </div>
                        <div class="col-sm-6">
                            <i class="bi bi-clock me-1"></i>
                            <strong>Timestamp:</strong> <?php echo e(now()->format('d M Y, H:i:s')); ?>

                        </div>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('activities.update', $activity)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 flex-wrap">
                            <?php $__currentLoopData = ['pending' => ['⏳','badge-pending','Pending'], 'in_progress' => ['🔄','badge-progress','In Progress'], 'done' => ['✅','badge-done','Done']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => [$icon, $cls, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="status-option flex-fill" style="cursor:pointer;">
                                <input type="radio" name="status" value="<?php echo e($value); ?>" class="d-none status-radio"
                                    <?php echo e(($activity->latestLog?->status ?? 'pending') === $value ? 'checked' : ''); ?>>
                                <div class="card text-center py-3 status-card border-2 <?php echo e(($activity->latestLog?->status ?? 'pending') === $value ? 'border-primary bg-light' : ''); ?>"
                                     style="border-radius:10px; transition:all .15s;">
                                    <div style="font-size:1.5rem;"><?php echo e($icon); ?></div>
                                    <div class="fw-semibold mt-1" style="font-size:.85rem;"><?php echo e($label); ?></div>
                                </div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger mt-1" style="font-size:.82rem;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Remark / Notes</label>
                        <textarea name="remark" class="form-control <?php $__errorArgs = ['remark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  rows="4" placeholder="Add any relevant notes, discrepancies found, or handover information..."><?php echo e(old('remark')); ?></textarea>
                        <?php $__errorArgs = ['remark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-save me-2"></i>Save Update
                        </button>
                        <a href="<?php echo e(route('activities.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        
        <?php if($activity->todayLogs->count()): ?>
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Today's Logs</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                <?php $__currentLoopData = $activity->todayLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $badgeMap = ['done'=>'badge-done','pending'=>'badge-pending','in_progress'=>'badge-progress']; ?>
                    <div class="timeline-item">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge rounded-pill <?php echo e($badgeMap[$log->status] ?? ''); ?>">
                                <?php echo e(str_replace('_',' ', ucfirst($log->status))); ?>

                            </span>
                            <strong style="font-size:.85rem;"><?php echo e($log->user->name); ?></strong>
                            <span style="font-size:.76rem; color:#aaa;"><?php echo e($log->logged_at->format('H:i:s')); ?></span>
                        </div>
                        <?php if($log->remark): ?>
                        <div class="text-muted mt-1" style="font-size:.82rem;"><?php echo e($log->remark); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CYRIL\Desktop\team-tracker\resources\views/activities/edit.blade.php ENDPATH**/ ?>