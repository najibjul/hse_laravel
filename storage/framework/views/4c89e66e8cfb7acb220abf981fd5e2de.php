<?php $__env->startSection('content'); ?>
    <div class="p-3 text-center">
        <h2>Setup Google Authenticator</h2>

        <p>Scan QR code berikut dengan aplikasi <b>Google Authenticator</b>:</p>

        <div class="mb-3">
            <?php echo $qrcode; ?>

        </div>

        <p>Atau masukkan kode rahasia manual:</p>
        <button type="button" onclick="copyToClipboard()" class="btn btn-sm btn-success rounded-pill">
            <code class="text-white"><?php echo e($secret); ?> <i class="ti ti-clipboard"></i></code>
        </button>
        <input type="text" id="secret" value="<?php echo e($secret); ?>" hidden>

        <hr>

        <form method="POST" action="<?php echo e(route('mfa.verify')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label>Masukkan kode OTP:</label>
                <input type="text" name="otp" class="form-control" placeholder="XXXXXX" required>
                <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" class="btn btn-success">Verifikasi</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function copyToClipboard() {
            var copyText = document.getElementById("secret");

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value);

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "Kode berhasil dicopy"
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/mfa/setup.blade.php ENDPATH**/ ?>