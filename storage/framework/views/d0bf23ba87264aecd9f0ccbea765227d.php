<?php $__env->startSection('content'); ?>
<div class="p-3">
<h2>Verifikasi OTP</h2>
<p>Masukkan kode 6 digit dari aplikasi Google Authenticator:</p>

<form method="POST" action="<?php echo e(route('mfa.verify')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <input type="text" name="otp" class="form-control" placeholder="XXXXXX" required>
        <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <button type="submit" class="btn btn-success">Verifikasi</button>
</form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/mfa/challenge.blade.php ENDPATH**/ ?>