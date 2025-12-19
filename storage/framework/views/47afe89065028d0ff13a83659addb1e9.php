<!DOCTYPE html>
<html lang="en">

<head>

    <title><?php echo e(isset($title) ? $title . ' • HSE' : 'HSE'); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="shortcut icon" href="<?php echo e(asset('HSE GT.png')); ?>" type="image/x-icon">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

</head>

<body data-title="<?php echo e($title ?? ''); ?>" data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light"
    id="main-font-link">

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <nav id="sidebar" class="pc-sidebar ">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="#" class="b-brand text-primary">
                    <img src="<?php echo e(asset('HSE GT.png')); ?>" class="img-fluid" style="width: 50%;" alt="logo">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item <?php echo e(route('dashboard') && 'active'); ?>">
                        <a href="<?php echo e(route('dashboard')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Safety Comitee</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item <?php echo e(Route::is('qrp.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('qrp.daily-checking')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-shield-check"></i></span>
                            <span class="pc-mtext">Daily Checking</span>
                        </a>
                    </li>

                    <?php if(auth()->user()->role_id == 2 or auth()->user()->role_id == 1): ?>
                        <li class="pc-item pc-caption">
                            <label>Admin Menu</label>
                            <i class="ti ti-dashboard"></i>
                        </li>
                        <li class="pc-item <?php echo e(Route::is('admin.users.*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-users"></i></span>
                                <span class="pc-mtext">Master User</span>
                            </a>
                        </li>
                        <?php if(auth()->user()->role_id == 1): ?>
                            <li class="pc-item <?php echo e(Route::is('admin.departments.*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('admin.departments.index')); ?>" class="pc-link">
                                    <span class="pc-micon"><i class="ti ti-home"></i></span>
                                    <span class="pc-mtext">Master Departemen</span>
                                </a>
                            </li>
                            <li class="pc-item <?php echo e(Route::is('admin.cost-centers.*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('admin.cost-centers.index')); ?>" class="pc-link">
                                    <span class="pc-micon"><i class="ti ti-currency-dollar"></i></span>
                                    <span class="pc-mtext">Master Cost Center</span>
                                </a>
                            </li>
                            <li class="pc-item <?php echo e(Route::is('admin.positions.*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('admin.positions.index')); ?>" class="pc-link">
                                    <span class="pc-micon"><i class="ti ti-grid-dots"></i></span>
                                    <span class="pc-mtext">Master Posisi</span>
                                </a>
                            </li>
                            <li class="pc-item <?php echo e(Route::is('admin.plants.*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('admin.plants.index')); ?>" class="pc-link">
                                    <span class="pc-micon"><i class="ti ti-building-skyscraper"></i></span>
                                    <span class="pc-mtext">Master Plant</span>
                                </a>
                            </li>
                            <li class="pc-item <?php echo e(Route::is('admin.dept-config.*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('admin.dept-config.index')); ?>" class="pc-link">
                                    <span class="pc-micon"><i class="ti ti-settings"></i></span>
                                    <span class="pc-mtext">Department Configuration</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a onclick="setSidebar()" href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ti ti-mail">
                                <?php if($totalNotification > 0): ?>
                                    <span class="badge bg-success rounded small"
                                        style="position: fixed; margin-top: -13px;  margin-left: -15px;">
                                        <?php echo e($totalNotification); ?></span>
                                <?php endif; ?>
                            </i>
                        </a>
                        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <h5 class="m-0">Pesan</h5>
                                <a href="#!" class="pc-head-link bg-transparent"><i
                                        class="ti ti-x text-danger"></i></a>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                                style="max-height: calc(100vh - 215px)">
                                <div class="list-group list-group-flush w-100">
                                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <form id="notificationUpdate<?php echo e($notification->id); ?>" method="POST"
                                            action="<?php echo e(route('notification.update', $notification->id)); ?>"
                                            class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('patch'); ?>
                                        </form>

                                        <a href="#" onclick="event.preventDefault(); document.getElementById(`notificationUpdate<?php echo e($notification->id); ?>`).submit();" class="list-group-item list-group-item-action">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="<?php echo e(asset('assets/images/user/avatar-2.jpg')); ?>" alt="user-image"
                                                        class="user-avtar">
                                                </div>
                                                <div class="flex-grow-1 ms-1">
                                                    
                                                    <p class="text-body mb-1"><?php echo e($notification->title); ?></p>
                                                    <span class="text-muted"><?php echo e($notification->body); ?></span>
                                                    <div class="text-muted mt-2"><?php echo e(\Carbon\Carbon::parse($notification->created_at)->diffForHumans()); ?></div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="flex-grow-1 ms-1 text-center">
                                            Tidak ada pesan
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside"
                            aria-expanded="false">
                            <img src="<?php echo e(asset('assets/images/user/avatar-2.jpg')); ?>" alt="user-image"
                                class="user-avtar">
                            <span><?php echo e(auth()->user()->name); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="<?php echo e(asset('/assets/images/user/avatar-2.jpg')); ?>" alt="user-image"
                                            class="user-avtar wid-35">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><?php echo e(auth()->user()->name); ?></h6>
                                        <span><?php echo e(auth()->user()->role->role_name); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" id="mysrpTabContent">
                                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                    aria-labelledby="drp-t1" tabindex="0">

                                    <a href="<?php echo e(route('profile.index')); ?>" class="dropdown-item">
                                        <i class="ti ti-user"></i>
                                        <span>Profil</span>
                                    </a>

                                    <a href="javascript:void(0)"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="dropdown-item">
                                        <i class="ti ti-power"></i>
                                        <span>Keluar</span>
                                    </a>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                        style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="pc-container">
        <div class="pc-content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>


    <footer class="pc-footer d-none d-md-block">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm my-1">
                    <p class="m-0">Copyright © EDP <?php echo e(now()->format('Y')); ?></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo e(asset('assets/js/plugins/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/fonts/custom-font.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/feather.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pcoded.js')); ?>"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let sidebarLs = localStorage.getItem('sidebarLs');
            let sidebarComp = document.getElementById('sidebar');

            if (!sidebarLs) {
                localStorage.setItem('sidebarLs', 'expand');
            } else {

                if (sidebarLs == 'expand') {
                    sidebarComp.classList.remove('pc-sidebar-hide')
                } else {
                    sidebarComp.classList.add('pc-sidebar-hide')
                }
            }


            <?php if(session()->has('success')): ?>
                Swal.fire({
                    title: "Berhasil",
                    text: `<?php echo e(session('success')); ?>`,
                    icon: "success",
                    confirmButtonColor: "#52c41a",
                });
            <?php endif; ?>

            <?php if(session()->has('error')): ?>
                Swal.fire({
                    title: "Error",
                    text: `<?php echo e(session('error')); ?>`,
                    icon: "error",
                    confirmButtonColor: "#ff4d4f"
                });
            <?php endif; ?>

            layout_change('light');
            change_box_container('false');
            layout_rtl_change('false');
            preset_change("preset-1");
            font_change("Public-Sans");
        })

        function setSidebar() {
            let sidebarLs = localStorage.getItem('sidebarLs');
            if (sidebarLs == 'expand') {
                localStorage.setItem('sidebarLs', 'collapse');
            } else {
                localStorage.setItem('sidebarLs', 'expand');
            }
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>
<?php /**PATH C:\laragon\www\hse\resources\views/layouts/app.blade.php ENDPATH**/ ?>