<?php $__env->startSection('content'); ?>

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <div class="m-b-10 fs-2 ">Dashboard</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-image:url('<?php echo e(asset('assets/images/dr12.jpg')); ?>'); background-size: cover; background-position: center;">
        <div class="card-body">
            <h4 class="text-success">Selamat datang <?php echo e(auth()->user()->name); ?></h4>
            <div class="text-muted"><?php echo e(\Carbon\Carbon::now()->translatedFormat('d M Y')); ?></div>

            <div class="mt-4 <?php echo e($todayChecked == 0 ? 'text-danger' : 'text-success'); ?>"> <?php if($todayChecked == 0): ?> <i class="ti ti-alert-circle"></i> <?php else: ?> <i class="ti ti-circle-check"></i> <?php endif; ?> <?php echo e($todayChecked == 0 ? 'Anda belum melakukan daily checking' : 'Hebat, Anda telah melakukan daily checking'); ?></div>

            <?php if($todayChecked == 0): ?>
                <a href="<?php echo e(route('qrp.daily-checking')); ?>" class="w-auto btn btn-light-success mt-3 rounded-pill">
                    Lakukan daily checking 
                    <i class="ti ti-chevron-right ms-3"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-1 mt-5 ms-3 text-secondary">Total Safety Commite</div>
    <div class="mb-3 fs-4 ms-3 text-uppercase "><?php echo e(\Carbon\Carbon::now()->subMonth()->format('M')); ?> - <?php echo e(\Carbon\Carbon::now()->format('M Y')); ?></div>

    <div class="row">
        <div class="col-6 col-xl-4">
            <div class="card border-0 border-top border-info border-3">
                <div class="card-body ">
                    <h6 class="mb-2 f-w-400 text-info">Menunggu</h6>
                    <h1 class="mb-3"><?php echo e($waiting); ?></h1>
                    <p class="mb-0 text-muted text-sm">Menunggu konfirmasi pelaksanaan & penyelesaian
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="card border-0 border-top border-primary border-3">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-primary">Sedang Dikerjakan</h6>
                    <h1 class="mb-3"><?php echo e($inProgress); ?></h1>
                    <p class="mb-0 text-muted text-sm">Laporan sedang ditindak lanjuti berdasarkan rekomendasi</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="card border-0 border-top border-success border-3">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-success">Close</h6>
                    <h1 class="mb-3"><?php echo e($close); ?></h1>
                    <p class="mb-0 text-muted text-sm">Laporan telah dikonfirmasi oleh pimpinan</p>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse_laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>