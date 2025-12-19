<?php $__env->startSection('content'); ?>
    <?php if(!$agent->isMobile()): ?>
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('qrp.daily-checking')); ?>">Daily Checking</a></li>
                            <li class="breadcrumb-item"><a
                                    href="<?php echo e(route('qrp.qrp-form-detail', encrypt($dailyCheck->id))); ?>">Detail laporan</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Edit laporan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card ">
                <div class="card-header">
                    <h4>Edit laporan</h4>
                </div>
                <form method="POST" action="<?php echo e(route('qrp.qrp-form-update', $dailyCheck->id)); ?>"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('patch'); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class=" mb-5">
                                <label class="form-label fw-bold">Faktor temuan</label>
                                <select name="factor" class="form-control" required>
                                    <?php $__currentLoopData = $factors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($factor->id); ?>"
                                            <?php echo e($factor->id == $dailyCheck->factor_id ? 'selected' : ''); ?>>
                                            <?php echo e($factor->factor_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class=" mb-5">
                                <label class="form-label fw-bold">Area temuan</label>
                                <input name="area" type="text"
                                    class="form-control <?php $__errorArgs = ['area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ketik disini ..."
                                    value="<?php echo e($dailyCheck->area); ?>" required>
                                <?php $__errorArgs = ['area'];
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

                            <div class=" mb-5">
                                <label class="form-label fw-bold">Deskripsi temuan</label>
                                <textarea name="description" id="description" oninput="autoGrowDescription(this)"
                                    class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ketik disini ..." required><?php echo e($dailyCheck->qrpDetail->description); ?></textarea>
                                <?php $__errorArgs = ['description'];
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

                            <div class=" mb-5">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="category" class="form-control" required>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>"
                                            <?php echo e($category->id == $dailyCheck->qrpDetail->category_id ? 'selected' : ''); ?>>
                                            <?php echo e($category->category_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['category'];
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

                            <div class=" mb-5">
                                <label class="form-label fw-bold">Gambar temuan</label>
                                <div id="existing-image" class="mt-2 mb-2 bg-light text-center py-3 rounded d-block">
                                    <img src="<?php echo e(asset('storage/image/' . $dailyCheck->qrpDetail->before)); ?>"
                                        alt="<?php echo e($dailyCheck->qrpDetail->before); ?>"
                                        class="img-fluid <?php if($agent->isDesktop()): ?> w-50 <?php endif; ?>">
                                    <div class="mt-2">
                                        <button onclick="editimage()" type="button"
                                            class="btn btn-outline-success rounded-pill">Edit gambar</button>
                                    </div>
                                </div>

                                <div class="d-none" id="edit-image">
                                    <input type="text" name="dataUri" id="dataUri" hidden>
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation" id="fotoLangsung">
                                            <button class="nav-link active" id="direct-tab" data-bs-toggle="tab"
                                                data-bs-target="#direct" type="button" role="tab"
                                                aria-controls="direct" aria-selected="true">Foto
                                                langsung</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="galeri-tab" data-bs-toggle="tab"
                                                data-bs-target="#galeri" type="button" role="tab"
                                                aria-controls="galeri" aria-selected="false">Dari
                                                Galeri</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active my-3 text-center" id="direct" role="tabpanel"
                                            aria-labelledby="direct-tab">
                                            <div class="d-flex justify-content-center align-item-center pt-3">
                                                <div id="my_camera"></div>
                                            </div>

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
                                            </div>
                                        </div>
                                        <div class="tab-pane fade my-3" id="galeri" role="tabpanel"
                                            aria-labelledby="galeri-tab">
                                            <input type="file" class="form-control" name="galery"
                                                value="<?php echo e(old('galery')); ?>">
                                        </div>

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
                                </div>

                            </div>

                            <div class=" mb-5">
                                <label class="form-label fw-bold">Rekomendasi</label>
                                <textarea name="recomendation" id="recomendation" oninput="autoGrowRecomendation(this)"
                                    class="form-control <?php $__errorArgs = ['recomendation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ketik disini ..." required><?php $__currentLoopData = $dailyCheck->qrpDetail->qrpRecomendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qrpRecomendation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($qrpRecomendation->recomendation); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></textarea>
                                <?php $__errorArgs = ['recomendation'];
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
                        </div>

                        <div class="form-group mb-5">
                            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                data-bs-target="#modalSave"><i class="ti ti-edit"></i> Edit</button>

                            <div class="modal fade" id="modalSave" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Update data?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Kembali</button>
                                            <button type="submit" class="btn btn-warning">Ya</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/webcam/webcam.min.js')); ?>" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Webcam.on('error', function(err) {
                $('#fotoLangsung').addClass('d-none');
                $('#galeri-tab').addClass('active');
                $('#direct').removeClass('show active');
                $('#galeri').addClass('show active');
            });

            <?php if($agent->isMobile()): ?>
                Webcam.set({
                    width: 240,
                    height: 320,
                    image_format: 'jpeg',
                    jpeg_quality: 90,
                    constraints: {
                        facingMode: "environment"
                    }
                });
            <?php else: ?>
                Webcam.set({
                    width: 320,
                    height: 240,
                    image_format: 'jpeg',
                    jpeg_quality: 90,
                    constraints: {
                        facingMode: "environment"
                    }
                });
            <?php endif; ?>

            Webcam.attach('#my_camera');

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
                });
            <?php endif; ?>
        });

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

        function autoGrowRecomendation(element) {
            element.style.height = "5px";
            element.style.height = (element.scrollHeight) + "px";
        }

        function editimage(){
            let editImage = document.getElementById('edit-image');
            let existingImage = document.getElementById('existing-image');
            editImage.classList.remove('d-none');
            editImage.classList.add('d-block');
            existingImage.classList.remove('d-block');
            existingImage.classList.add('d-none');
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Edit laporan'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/qrp/qrp-form-detail-edit.blade.php ENDPATH**/ ?>