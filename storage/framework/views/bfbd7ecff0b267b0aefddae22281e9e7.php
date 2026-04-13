<?php $__env->startSection('content'); ?>
    <div class="page-header" id="master-user-index">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Master User</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Master User</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="d-flex justify-content-end">
                    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-sm btn-success rounded">
                        <i class="ti ti-plus"></i> Tambah
                    </a>

                    <button type="button" id="userExport" class="btn btn-sm btn-info rounded ms-2"><i class="ti ti-file"></i> Export</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="userTable">
                    <thead class="table-success">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>Cost Center</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Position</th>
                            <th>Plant</th>
                            <th>Leader</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Master User'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse_laravel\resources\views/admin/users/index.blade.php ENDPATH**/ ?>