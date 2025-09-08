<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Admin - Department</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dept-config.index')); ?>">Admin - Department</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Edit</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="data-admin" data-admin="<?php echo e($user->id); ?>">
        <div class="card-header">
            <h4>Edit</h4>
        </div>
        <div class="card-body">
            <div class="bg-light rounded p-4">
                <div>Nama : <?php echo e($user->name); ?></div>
                <div>NIP : <?php echo e($user->nip); ?></div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <div class="fw-bold">AKSES DEPARTEMEN</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mt-4">
                                <table class="table" id="akses-departemen-table" >
                                    <thead class="table-success">
                                        <tr>
                                            <th>No</th>
                                            <th>Departemen</th>
                                            <th class="text-center">Opsi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <div class="fw-bold">MASTER DEPARTEMEN</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mt-4">
                                <table class="table" id="master-departemen-table" >
                                    <thead class="table-success">
                                        <tr>
                                            <th>No</th>
                                            <th>Departemen</th>
                                            <th class="text-center">Opsi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Department Configuration'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/admin/dept-config/edit.blade.php ENDPATH**/ ?>