<?php $__env->startSection('content'); ?>
    <div class=" mt-4">
        <div class="card rounded">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div>
                        <h4 class="mb-0"><?php echo e(Auth::user()->name); ?></h4>
                        <small class="text-muted"><?php echo e(Auth::user()->email); ?></small>
                    </div>
                </div>

                <hr>
                <p><strong>Nama : </strong> <?php echo e(Auth::user()->name); ?></p>
                <p><strong>NIP : </strong> <?php echo e(Auth::user()->nip); ?></p>
                <p><strong>Email : </strong> <?php echo e(Auth::user()->email); ?></p>
                <p><strong>Departemen : </strong> <?php echo e(Auth::user()->department->department_name); ?></p>
                <p><strong>Role : </strong> <?php echo e(Auth::user()->role->role_name); ?></p>
                <p><strong>Jabatan : </strong> <?php echo e(Auth::user()->position->position_name); ?></p>

                <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#edit">
                    Ganti Password
                </button>

                <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Ganti password</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                                <div class="modal-body">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>

                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Password Saat Ini</label>
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " required
                                            oninput="validatePasswords()">
                                        <?php $__errorArgs = ['current_password'];
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

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" name="password" id="password"
                                            class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " required
                                            oninput="validatePasswords()">
                                        <?php $__errorArgs = ['password'];
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

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password
                                            Baru</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> "
                                            required oninput="validatePasswords()">
                                        <?php $__errorArgs = ['password_confirmation'];
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

                                    <div id="passwordValidation" class="my-2">
                                        <small id="validationMessage" class="text-danger"></small>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" id="submitBtn" class="btn btn-success" disabled>Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function validatePasswords() {
            const currentPassword = document.getElementById('current_password').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const submitBtn = document.getElementById('submitBtn');
            const validationMessage = document.getElementById('validationMessage');

            // Validasi
            const currentPasswordValid = currentPassword.length > 0;
            const requiredValid = password.length > 0;
            const minValid = password.length >= 8;
            const uppercaseValid = /[A-Z]/.test(password);
            const lowercaseValid = /[a-z]/.test(password);
            const numberValid = /[0-9]/.test(password);
            const symbolValid = /[@$!%*#?&.,]/.test(password);
            const confirmValid = password === passwordConfirmation && password.length > 0;

            // Buat array pesan error
            const errors = [];
            if (!currentPasswordValid) errors.push('Password saat ini wajib diisi');
            if (!requiredValid) errors.push('Password baru wajib diisi');
            if (!minValid) errors.push('Min 8 karakter');
            if (!uppercaseValid) errors.push('Huruf besar');
            if (!lowercaseValid) errors.push('Huruf kecil');
            if (!numberValid) errors.push('Angka');
            if (!symbolValid) errors.push('Simbol');
            if (!confirmValid && (password.length > 0 || passwordConfirmation.length > 0)) errors.push(
                'Password tidak sama');

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
            const allValid = currentPasswordValid && requiredValid && minValid && uppercaseValid && lowercaseValid &&
                numberValid && symbolValid && confirmValid;
            submitBtn.disabled = !allValid;
        }

        // Jalankan validasi saat modal ditampilkan
        document.addEventListener('DOMContentLoaded', () => {
            const editModal = document.getElementById('edit');
            if (editModal) {
                editModal.addEventListener('shown.bs.modal', validatePasswords);
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['title' => auth()->user()->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/profile.blade.php ENDPATH**/ ?>