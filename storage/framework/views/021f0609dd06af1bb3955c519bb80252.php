<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - HSE</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="shortcut icon" href="<?php echo e(asset('HSE GT.png')); ?>" type="image/x-icon">
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
                    <a href="#"><img src="<?php echo e(asset('HSE GT.png')); ?>" alt="img"></a>
                </div>
                <div class="card my-5 shadow">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
                <div class="auth-footer row">
                    <div class="col my-1">
                        <p class="m-0">Copyright © <a href="#">EDP <?php echo e(now()->format('Y')); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    
</body>

</html>
<?php /**PATH C:\laragon\www\hse\resources\views/layouts/auth.blade.php ENDPATH**/ ?>