<?php $__env->startSection('content'); ?>
    <div class="p-3">
        <h2>Ganti Password</h2>
        <p class="mb-4">Ubah password terlebih dahulu agar dapat mengakses aplikasi</p>

        <form action="<?php echo e(route('change-password.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-2">
                <label for="old-password">Password saat ini</label>
                <input type="password" class="form-control <?php $__errorArgs = ['old_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="old_password" id="old-password" placeholder="XXXXXXXX" required oninput="validatePasswords()">
                <?php $__errorArgs = ['old_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger">
                    <?php echo e($message); ?>

                </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-2">
                <label for="password">Password baru</label>
                <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" id="password" placeholder="XXXXXXXX" required oninput="validatePasswords()">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger">
                    <?php echo e($message); ?>

                </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            </div>
            <div class="mb-4">
                <label for="password-confirmation">Konfirmasi password baru</label>
                <input type="password" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password_confirmation" id="password-confirmation" placeholder="XXXXXXXX" required oninput="validatePasswords()">
                <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger">
                    <?php echo e($message); ?>

                </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div id="passwordValidation" class="my-2">
                    <small id="validationMessage" class="text-danger"></small>
                </div>
            <button type="submit" id="submitBtn" class="btn btn-warning w-100" disabled>Update</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function validatePasswords() {
            const oldPassword = document.getElementById('old-password').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password-confirmation').value;
            const submitBtn = document.getElementById('submitBtn');
            const validationMessage = document.getElementById('validationMessage');

            // Validasi
            const oldPasswordValid = oldPassword.length > 0;
            const requiredValid = password.length > 0;
            const minValid = password.length >= 8;
            const uppercaseValid = /[A-Z]/.test(password);
            const lowercaseValid = /[a-z]/.test(password);
            const numberValid = /[0-9]/.test(password);
            const symbolValid = /[@$!%*#?&.,]/.test(password);
            const confirmValid = password === passwordConfirmation && password.length > 0;

            // Buat array pesan error
            const errors = [];
            if (!oldPasswordValid) errors.push('Password lama wajib diisi');
            if (!requiredValid) errors.push('Password baru wajib diisi');
            if (!minValid) errors.push('Min 8 karakter');
            if (!uppercaseValid) errors.push('Huruf besar');
            if (!lowercaseValid) errors.push('Huruf kecil');
            if (!numberValid) errors.push('Angka');
            if (!symbolValid) errors.push('Simbol');
            if (!confirmValid && (password.length > 0 || passwordConfirmation.length > 0)) errors.push('Password tidak sama');

            // Tampilkan pesan
            if (errors.length > 0) {
                validationMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errors.join(', ');
                validationMessage.classList.remove('text-success');
                validationMessage.classList.add('text-danger');
            } else {
                validationMessage.innerHTML = '<i class="fas fa-check-circle"></i> Semua kriteria terpenuhi';
                validationMessage.classList.remove('text-danger');
                validationMessage.classList.add('text-success');
            }

            // Enable/Disable Submit Button
            const allValid = oldPasswordValid && requiredValid && minValid && uppercaseValid && lowercaseValid && numberValid && symbolValid && confirmValid;
            submitBtn.disabled = !allValid;
        }

        // Jalankan validasi saat halaman load
        document.addEventListener('DOMContentLoaded', validatePasswords);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse_laravel\resources\views/change-password.blade.php ENDPATH**/ ?>