<?php $__env->startSection('content'); ?>
    <?php if(!$agent->isMobile()): ?>
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Detail</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Safety Comitee</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('qrp.daily-checking')); ?>">Daily Checking</a></li>
                            <li class="breadcrumb-item" aria-current="page">Detail</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h4>Detail</h4>
        </div>
        <div class="card-body">


                <div class="d-flex justify-content-center mb-4 gap-3">
                    
                    <a href="#" class="text-center text-success">
                        <div class="rounded-circle">
                            <div class="bg-success rounded-circle py-2 px-3">
                                <h3 class="mt-1">
                                    <i class="ti ti-writing text-light"></i>
                                </h3>
                            </div>
                        </div>
                        Dibuat
                    </a>

                    <div class="align-self-center py-1 px-2 <?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 2 ? 'bg-success border border-success' : 'bg-light border border-secondary'); ?> rounded"></div>
                    <a href="#" class="position-relative d-flex justify-content-center ">
                        <div class="rounded-circle ">
                            <div class="<?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 2 ? 'bg-success' : 'border border-secondary bg-light'); ?> rounded-circle py-2 px-3">
                                <h3 class="mt-1">
                                    <i class="<?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 2 ? 'text-white' : 'text-dark'); ?> ti ti-run "></i>
                                </h3>
                            </div>
                        </div>
                        <div class="position-absolute align-self-end text-success">
                        Dikerjakan
                        </div>
                    </a>

                    <div class="align-self-center py-1 px-2 <?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 4 ? 'bg-success border border-success' : 'bg-light border border-secondary'); ?> rounded"></div>
                    <a href="#" class="position-relative d-flex justify-content-center ">
                        <div class="rounded-circle ">
                            <div class="<?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 4 ? 'bg-success' : 'border border-secondary bg-light'); ?> rounded-circle py-2 px-3">
                                <h3 class="mt-1">
                                    <i class="<?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 4 ? 'text-white' : 'text-dark'); ?> ti ti-zoom-check "></i>
                                </h3>
                            </div>
                        </div>
                        <div class="position-absolute align-self-end text-success">
                        Konfirmasi
                        </div>
                    </a>

                    <div class="align-self-center py-1 px-2 <?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 5 ? 'bg-success border border-success' : 'bg-light border border-secondary'); ?> rounded"></div>
                    <a href="#" class="position-relative d-flex justify-content-center ">
                        <div class="rounded-circle ">
                            <div class="<?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 5 ? 'bg-success' : 'border border-secondary bg-light'); ?> rounded-circle py-2 px-3">
                                <h3 class="mt-1">
                                    <i class="<?php echo e($dailyCheck->qrpDetail->qrp_status_id >= 5 ? 'text-white' : 'text-dark'); ?> ti ti-checks "></i>
                                </h3>
                            </div>
                        </div>
                        <div class="position-absolute align-self-end text-success">
                        Selesai
                        </div>
                    </a>
                </div>    

            <?php if($dailyCheck->qrpDetail->closed_at): ?>
                <div class="text-end">
                    <label class="form-label">Close :
                        <?php echo e(\Carbon\Carbon::parse($dailyCheck->qrpDetail->closed_at)->translatedFormat('d M Y H:i:s')); ?></label>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">User</label>
                    <input type="text" class="form-control" disabled
                        value="<?php echo e($dailyCheck->user->name); ?> <?php echo e('(' . $dailyCheck->user->nip . ')'); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Tanggal temuan</label>
                    <input type="text" class="form-control" disabled
                        value="<?php echo e(\Carbon\Carbon::parse($dailyCheck->created_at)->translatedFormat('d M Y H:i')); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Departemen</label>
                    <input type="text" class="form-control" disabled
                        value="<?php echo e($dailyCheck->department->department_name); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Faktor temuan</label>
                    <input type="text" class="form-control" disabled
                        value="<?php echo e(strtoupper($dailyCheck->factor->factor_name)); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Area temuan</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="<?php echo e($dailyCheck->area); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Deskripsi temuan</label>
                    <textarea class="form-control" disabled oninput="autoGrowDescription(this)" id="description" placeholder="ketik disini"><?php echo e($dailyCheck->qrpDetail->description); ?></textarea>
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Kategori</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="<?php echo e($dailyCheck->qrpDetail->category->category_name); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Rank</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="<?php echo e($dailyCheck->qrpDetail->rank->rank_name); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Rekomendasi</label>
                    <br>
                    <ul>
                        <?php $__currentLoopData = $dailyCheck->qrpDetail->qrpRecomendations->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qrpRecomendation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div style="text-align: justify;">
                                <?php echo e($qrpRecomendation->recomendation); ?>

                            </div>
                            <span class="text-sm fst-italic text-secondary"><?php echo e($qrpRecomendation->user->name); ?> (<?php echo e($qrpRecomendation->user->nip); ?>)
                            </span>
                            <br>
                            <span class="text-sm fst-italic text-secondary">
                                <?php echo e(\Carbon\Carbon::parse($qrpRecomendation->created_at)); ?>

                            </span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Batas perbaikan</label>
                    <input class="form-control" disabled
                        value="<?php echo e(\Carbon\Carbon::parse($dailyCheck->qrpDetail->due_date)->format('d M Y')); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Temuan sebelum diaction</label>
                    <img src="<?php echo e(asset('storage/image/' . $dailyCheck->qrpDetail->before)); ?>" class="img-thumbnail"
                        alt="<?php echo e($dailyCheck->qrpDetail->before); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Temuan sesudah diaction</label>
                    <?php if($dailyCheck->qrpDetail->after): ?>
                        <img src="<?php echo e(asset('storage/image/' . $dailyCheck->qrpDetail->after)); ?>" class="img-thumbnail"
                            alt="<?php echo e($dailyCheck->qrpDetail->after); ?>">

                        <label class="form-label">Tgl. upload : <?php echo e($dailyCheck->qrpDetail->after_uploaded_at); ?></label>

                        <?php if($dailyCheck->user_id == auth()->user()->id && $dailyCheck->qrpDetail->qrp_status_id == 4): ?>
                            <br>
                            <label class="form-label mt-5 fw-bold">Edit penyelesaian</label>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation" id="fotoLangsung">
                                    <button class="nav-link active" id="direct-tab" data-bs-toggle="tab"
                                        data-bs-target="#direct" type="button" role="tab" aria-controls="direct"
                                        aria-selected="true">Foto langsung</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="galeri-tab" data-bs-toggle="tab"
                                        data-bs-target="#galeri" type="button" role="tab" aria-controls="galeri"
                                        aria-selected="false">Dari
                                        Galeri</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active my-3" id="direct" role="tabpanel"
                                    aria-labelledby="direct-tab">
                                    <div id="my_camera"></div>


                                    <div id="pre_take_buttons">
                                        <button type="button" class="btn btn-success mt-3" onClick="preview_snapshot()">
                                            <i class="ti ti-camera"></i> Ambil gambar
                                        </button>
                                    </div>

                                    <div id="post_take_buttons" style="display:none">
                                        <button type="button" class="btn  btn-warning mt-3" onClick="cancel_preview()">
                                            <i class="ti ti-arrow-back-up"></i> Ambil ulang gambar
                                        </button>
                                        <div>
                                            <button type="button" class="btn btn-warning mt-3 " data-bs-toggle="modal"
                                                data-bs-target="#updateClose"><i class="ti ti-device-floppy "></i>
                                                Update gambar penyelesaian</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade my-3" id="galeri" role="tabpanel"
                                    aria-labelledby="galeri-tab">
                                    <form action="<?php echo e(route('qrp.upload-close-galery-edit', $dailyCheck->id)); ?>"
                                        method="POST" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <input type="file" required
                                            class="form-control  <?php $__errorArgs = ['galery'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="galery">
                                        <button type="submit" class="btn btn-warning mt-3 ">Update gambar</button>
                                    </form>
                                </div>
                            </div>

                            <div class="modal fade" id="updateClose" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Penyelesaian</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Update foto penyelesaian?
                                        </div>
                                        <form action="<?php echo e(route('qrp.upload-close-edit', $dailyCheck->id)); ?>"
                                            method="POST" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <input type="text" name="dataUri" id="dataUri" hidden>
                                                <button type="submit" class="btn btn-warning">Ya</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if($dailyCheck->qrpDetail->qrpApprovals->sortByDesc('id')->first()->approved_at): ?>
                            <h5 class="mt-1">
                                <span class="badge bg-warning text-white">BELUM ADA ACTION</span>
                            </h5>

                            <?php if($dailyCheck->qrpDetail->qrpApprovals->sortByDesc('id')->first()->approved_at and $dailyCheck->user_id == auth()->user()->id): ?>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation" id="fotoLangsung">
                                        <button class="nav-link active" id="direct-tab" data-bs-toggle="tab"
                                            data-bs-target="#direct" type="button" role="tab"
                                            aria-controls="direct" aria-selected="true">Foto langsung</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="galeri-tab" data-bs-toggle="tab"
                                            data-bs-target="#galeri" type="button" role="tab"
                                            aria-controls="galeri" aria-selected="false">Dari
                                            Galeri</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active my-3" id="direct" role="tabpanel"
                                        aria-labelledby="direct-tab">
                                        <div id="my_camera"></div>
                                        <form action="<?php echo e(route('qrp.upload-close', $dailyCheck->id)); ?>" method="POST"
                                            enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <input type="text" name="dataUri" id="dataUri" hidden>

                                            <div id="pre_take_buttons">
                                                <button type="button" class="btn btn-success mt-3"
                                                    onClick="preview_snapshot()">
                                                    <i class="ti ti-camera"></i> Ambil gambar
                                                </button>
                                            </div>

                                            <div id="post_take_buttons" style="display:none">
                                                <button type="button" class="btn btn-warning mt-3"
                                                    onClick="cancel_preview()">
                                                    <i class="ti ti-arrow-back-up"></i> Ambil ulang gambar
                                                </button>
                                                <div>
                                                    <button type="submit" class="btn btn-success mt-3">
                                                        <i class="ti ti-device-floppy"></i> Simpan gambar penyelesaian
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade my-3" id="galeri" role="tabpanel"
                                        aria-labelledby="galeri-tab">
                                        <form action="<?php echo e(route('qrp.upload-close-galery', $dailyCheck->id)); ?>"
                                            method="POST" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <input type="file" required
                                                class="form-control <?php $__errorArgs = ['galery'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="galery">
                                            <button type="submit" class="btn btn-success mt-3">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php $__errorArgs = ['dataUri'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-text text-danger mb-3"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['galery'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-text text-danger mb-3"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Approval</label>
                    <ul>
                        <?php $__currentLoopData = $dailyCheck->qrpDetail->qrpApprovals->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qrpApproval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <div>
                                    <?php echo e($qrpApproval->approval->name); ?> (<?php echo e($qrpApproval->approval->nip); ?>)
                                </div>
                                

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                

            </div>

            <?php if(auth()->user()->id == $dailyCheck->user_id && $dailyCheck->qrpDetail->qrp_status_id == 1): ?>
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?php echo e(route('qrp.qrp-form-detail.edit', encrypt($dailyCheck->id))); ?>"
                        class="btn btn-warning"><i class="ti ti-edit"></i> Edit Data</a>


                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete"><i
                            class="ti ti-trash"></i> Hapus</button>

                </div>

                <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Hapus Safety Comitee</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="<?php echo e(route('qrp.qrp-form-detail.destroy', $dailyCheck->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <div class="modal-body">
                                    Hapus data safety comitee?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-danger">Ya</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($dailyCheck->qrpDetail->qrp_status_id == 1 && $dailyCheck->qrpDetail->qrpApprovals->sortByDesc('id')->first()->approval_id == auth()->user()->id): ?>
                <div class="d-flex justify-content-center gap-1 mt-4">
                    


                    <button class="btn btn-sm btn-success " data-bs-toggle="modal" data-bs-target="#confirm">Konfirmasi
                    </button>

                    <button class="btn btn-sm btn-info text-nowrap " data-bs-toggle="modal"
                        data-bs-target="#confirmationModal">Revisi rekomendasi </button>

                    <?php if(auth()->user()->position_id != 1): ?>
                        <button class="btn btn-warning btn-sm " data-bs-toggle="modal" data-bs-target="#riseup">Rise
                            Up</button>
                    <?php endif; ?>

                </div>

                <div class="modal fade" id="confirm" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Lanjut perbaikan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="<?php echo e(route('qrp.confirm', $dailyCheck->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="modal-body">
                                    Lanjut perbaikan sesuai rekomendasi?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-success">Konfirmasi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="riseup" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Rise Up</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="<?php echo e(route('rise-up', $dailyCheck->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div>Rise up ke <b><i><?php echo e(auth()->user()->leader?->name); ?> (<?php echo e(auth()->user()->leader?->nip); ?>)</i></b>?</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                        <input type="text" name="riseup" class="d-none" value="<?php echo e(auth()->user()->leader_id); ?>">
                                    <button type="submit" class="btn btn-warning">Rise Up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Lanjut perbaikan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="<?php echo e(route('qrp.approval', $dailyCheck->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">Revisi rekomendasi</label>
                                        <textarea name="recomendation" required class="form-control <?php $__errorArgs = ['recomendation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="ketik disini"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-info">Revisi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="cancel" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cancel</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="<?php echo e(route('qrp.dh-cancel', $dailyCheck->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="modal-body">
                                    Berikan rekomendasi & alasan?
                                    <textarea name="recomendation" class="form-control <?php $__errorArgs = ['recomendation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> "><?php echo e($dailyCheck->qrpDetail->recomendation); ?></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-warning">Ya</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($dailyCheck->qrpDetail->qrpApprovals->sortByDesc('id')->first()->approval_id == auth()->user()->id and $dailyCheck->qrpDetail->qrp_status_id == 4): ?>
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#open">TOLAK</button>
                        <button class="btn btn-success " data-bs-toggle="modal" data-bs-target="#close">CLOSE
                            LAPORAN</button>
                    </div>

                    <div class="modal fade" id="open" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">TOLAK OPEN</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="<?php echo e(route('qrp.tolak-open', $dailyCheck->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="form-label">Alasan & rekomendasi ulang</label>
                                            <textarea name="recomendation" id="recomendation" required
                                                class="form-control <?php $__errorArgs = ['recomendation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ketik disini"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="close" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">CLOSE QRP</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="<?php echo e(route('qrp.close', $dailyCheck->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-body">
                                        Close laporan QRP?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-success">Ya</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/webcam/webcam.min.js')); ?>" defer></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Webcam.on('error', function(err) {
                $('#fotoLangsung').addClass('d-none');
                $('#galeri-tab').addClass('active');
                $('#direct').removeClass('show active');
                $('#galeri').addClass('show active');
            });

            Webcam.set({
                width: 240,
                height: 320,
                image_format: 'jpeg',
                jpeg_quality: 90,
                constraints: {
                    facingMode: "environment"
                }
            });

            if (document.getElementById('my_camera')) {
                Webcam.attach('#my_camera');
            }

            navigator.mediaDevices.enumerateDevices()
                .then(devices => {
                    devices.forEach(device => {
                        if (device.kind === 'videoinput') {
                            console.log(device.label, device.deviceId);
                        }
                    });
                });


            const recomendation = document.getElementById("recomendation");
            const description = document.getElementById("description");

            autoGrowDescription(description);

            <?php if($errors->any()): ?>
                let errorList = '';
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    errorList += `• <?php echo e($error); ?>\n`;
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    text: 'Silakan periksa form Anda.',
                    footer: `<pre style="text-align: left;">${errorList}</pre>`,
                    confirmButtonColor: "#dc3545"
                });
            <?php endif; ?>
        })

        function preview_snapshot() {

            Webcam.snap(function(data_uri) {
                document.getElementById('dataUri').value = data_uri;
            });

            Webcam.freeze();

            document.getElementById('pre_take_buttons').style.display = 'none';
            document.getElementById('post_take_buttons').style.display = '';
        }

        function cancel_preview() {
            document.getElementById('dataUri').value = "";

            Webcam.unfreeze();

            document.getElementById('pre_take_buttons').style.display = '';
            document.getElementById('post_take_buttons').style.display = 'none';
        }

        function save_photo() {
            Webcam.snap(function(data_uri) {
                document.getElementById('results').innerHTML =
                    '<h2>Here is your image:</h2>' +
                    '<img src="' + data_uri + '"/>';

                document.getElementById('pre_take_buttons').style.display = '';
                document.getElementById('post_take_buttons').style.display = 'none';
            });
        }

        function autoGrowDescription(element) {
            element.style.height = "5px";
            element.style.height = (element.scrollHeight) + "px";
        }
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
    <style type="text/css">
        body {
            font-family: Helvetica, sans-serif;
        }

        h2,
        h3 {
            margin-top: 0;
        }

        form {
            margin-top: 15px;
        }

        form input {
            margin-right: 15px;
        }

        #results {
            float: right;
            margin: 20px;
            padding: 20px;
            border: 1px solid;
            background: #ccc;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Detail'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/qrp/qrp-form-detail.blade.php ENDPATH**/ ?>