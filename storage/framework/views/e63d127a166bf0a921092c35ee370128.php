
<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Master Position</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Master Position</a></li>
                        <li class="breadcrumb-item" aria-current="page">Master Position</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Master Position</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="d-flex justify-content-end">
                    <a href="<?php echo e(route('admin.positions.create')); ?>" class="btn btn-sm btn-success rounded ms-2">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                    <button type="button" id="positionExport" class="btn btn-sm btn-info rounded ms-2"><i
                            class="ti ti-file"></i> Export</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="positionTable">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Safety Comitee</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Master Position'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/admin/positions/index.blade.php ENDPATH**/ ?>