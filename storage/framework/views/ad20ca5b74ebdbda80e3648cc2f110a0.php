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
                <div class="position-relative">
                    <input name="password" id="password" type="password"
                        class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Masukan kata sandi disini..." required style="padding-right: 2.75rem;">
                    <span id="togglePassword"
                        class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                        style="cursor: pointer; line-height: 1;" aria-label="Tampilkan kata sandi"
                        aria-pressed="false" role="button" tabindex="0">
                        <i class="ti ti-eye" id="togglePasswordIcon"></i>
                    </span>
                </div>
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
                
                <button type="submit" id="submitBtn" class="btn btn-success" >Login</button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (!passwordInput || !toggleButton || !toggleIcon) {
                return;
            }

            toggleButton.addEventListener('click', function() {
                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';
                toggleIcon.className = isHidden ? 'ti ti-eye-off' : 'ti ti-eye';
                toggleButton.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' :
                    'Tampilkan kata sandi');
                toggleButton.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            });

            toggleButton.addEventListener('keydown', function(event) {
                if (event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }

                event.preventDefault();
                toggleButton.click();
            });
        });
    </script>
    
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/login.blade.php ENDPATH**/ ?>