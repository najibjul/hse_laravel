<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - HSE</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    <a href="#"><img src="/assets/images/logo-dark.svg" alt="img"></a>
                </div>
                <div class="card my-5 shadow">
                    <form action="<?php echo e(route('login-post')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-end mb-4">
                                <h3 class="mb-0"><b>Login</b></h3>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">NIP</label>
                                <input name="nip" type="text"
                                    class="form-control <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="User NIP"
                                    required>
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
                                <label class="form-label">Password</label>
                                <input name="password" type="password"
                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Password"
                                    required>
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
                                    <input class="form-check-input input-success" type="checkbox" id="customCheckc1"
                                        name="remember">
                                    <label class="form-check-label text-muted" for="customCheckc1">Keep me sign
                                        in</label>
                                </div>
                            </div>
                            <?php if(session()->has('unauthenticated')): ?>
                                <div class="alert alert-danger mt-4" role="alert">
                                    <?php echo e(session('unauthenticated')); ?>

                                </div>
                            <?php endif; ?>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="auth-footer row">
                    <div class="col my-1">
                        <p class="m-0">Copyright © <a href="#">EDP <?php echo e(now()->format('Y')); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\laragon\www\hse\resources\views/login.blade.php ENDPATH**/ ?>