<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('login-post')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="mb-0"><b>Login</b></h3>
            </div>
            <div class="form-group mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input id="nip" name="nip" type="text" class="form-control <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="Masukan NIP disini..." required value="<?php echo e(old('nip')); ?>">
                <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Kata sandi</label>
                <input name="password" id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="Masukan kata sandi disini..." required oninput="validatePassword()">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div id="passwordValidation" class="mt-2">
                    <small id="validationMessage" class="text-danger"></small>
                </div>
            </div>
            <div class="d-flex mt-1 justify-content-between">
                <div class="form-check">
                    <input class="form-check-input input-success" type="checkbox" id="customCheckc1" name="remember">
                    <label class="form-check-label text-muted" for="customCheckc1">Ingat saya</label>
                </div>
            </div>
            <?php if(session()->has('unauthenticated')): ?>
                <div class="alert alert-danger mt-4" role="alert">
                    <?php echo e(session('unauthenticated')); ?>

                </div>
            <?php endif; ?>
            <div class="d-grid mt-4">
                <button type="submit" id="submitBtn" class="btn btn-success" disabled>Login</button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function validatePassword() {
            const nip = document.getElementById('nip').value.trim();
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submitBtn');
            const validationMessage = document.getElementById('validationMessage');

            const nipValid = nip.length > 0;
            const requiredValid = password.length > 0;
            const minValid = password.length >= 8;
            const uppercaseValid = /[A-Z]/.test(password);
            const lowercaseValid = /[a-z]/.test(password);
            const numberValid = /[0-9]/.test(password);
            const symbolValid = /[@$!%*#?&.,]/.test(password);

            const errors = [];
            if (!nipValid) errors.push('NIP wajib diisi');
            if (!requiredValid) errors.push('Password wajib diisi');
            if (!minValid) errors.push('Min 8 karakter');
            if (!uppercaseValid) errors.push('Huruf besar');
            if (!lowercaseValid) errors.push('Huruf kecil');
            if (!numberValid) errors.push('Angka');
            if (!symbolValid) errors.push('Simbol');

            if (errors.length > 0) {
                validationMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errors.join(', ');
                validationMessage.classList.remove('text-success');
                validationMessage.classList.add('text-danger');
            } else {
                validationMessage.innerHTML = '<i class="fas fa-check-circle"></i> Semua kriteria terpenuhi';
                validationMessage.classList.remove('text-danger');
                validationMessage.classList.add('text-success');
            }

            const allValid = nipValid && requiredValid && minValid && uppercaseValid && lowercaseValid && numberValid && symbolValid;
            submitBtn.disabled = !allValid;
        }

        document.getElementById('nip').addEventListener('input', validatePassword);

        document.addEventListener('DOMContentLoaded', validatePassword);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/login.blade.php ENDPATH**/ ?>