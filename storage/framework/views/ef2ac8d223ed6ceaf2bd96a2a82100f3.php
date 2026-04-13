<?php $__env->startSection('content'); ?>
    <div class="page-header d-lg-block d-none">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Safety Comitee Form</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Safety Comitee</a>
                        </li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('qrp.daily-checking')); ?>">Daily Checking</a></li>
                        <li class="breadcrumb-item" aria-current="page">Safety Comitee Form</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-9">
        <div class="card ">
            <div class="card-header">
                <h4>Safety Comitee Form</h4>
            </div>
            <form method="POST" action="<?php echo e(route('qrp.qrp-form-post')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="card-body">

                    <?php if(session()->has('error')): ?>
                    <?php echo e(session('error')); ?>

                    <?php endif; ?>

                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Faktor temuan</label>
                            <input type="text" class="form-control" disabled value="<?php echo e($factor->factor_name); ?>">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Area temuan</label>
                            <input name="area" type="text" class="form-control <?php $__errorArgs = ['area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="ketik disini ..." value="<?php echo e(old('area')); ?>" required>
                            <?php $__errorArgs = ['area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-text text-danger text-md mb-3"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Deskripsi temuan</label>
                            <textarea name="description" id="description" oninput="autoGrowDescription(this)"
                                class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ketik disini ..." required><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-text text-danger text-md mb-3"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category" class="form-control" required>
                                <option value="">--</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"
                                        <?php echo e(old('category') == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->category_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-text text-danger text-md mb-3"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Rank</label>
                            <?php $__currentLoopData = $ranks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check mb-3 d-flex gap-3">
                                    <input required type="radio" class="form-check-input" name="rank"
                                        id="rank<?php echo e($rank->id); ?>" value="<?php echo e($rank->id); ?>"
                                        <?php echo e(old('rank') == $rank->id ? 'checked' : ''); ?>>
                                    <label for="rank<?php echo e($rank->id); ?>"
                                        class="form-check-label fw-bold"><?php echo e($rank->rank_name); ?>.
                                    </label>
                                    <label for="rank<?php echo e($rank->id); ?>"
                                        class="form-check-label"><?php echo e($rank->rank_description); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $__errorArgs = ['rank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-text text-danger text-md mb-3"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Gambar temuan</label>
                            <input type="text" name="dataUri" id="dataUri" hidden>

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" id="fotoLangsung" role="presentation">
                                    <button class="nav-link active" id="direct-tab" data-bs-toggle="tab"
                                        data-bs-target="#direct" type="button" role="tab" aria-controls="direct"
                                        aria-selected="true">Foto langsung</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="galeri-tab" data-bs-toggle="tab" data-bs-target="#galeri"
                                        type="button" role="tab" aria-controls="galeri" aria-selected="false">Dari
                                        Galeri</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active my-1 text-center" id="direct" role="tabpanel"
                                    aria-labelledby="direct-tab">
                                    <div class="d-flex justify-content-center align-item-center pt-3" >
                                        <div id="my_camera"></div>
                                    </div>

                                    <div id="pre_take_buttons">
                                        <button type="button" class="btn btn-success mt-3" onClick="preview_snapshot()">
                                            <i class="ti ti-camera"></i> Ambil gambar
                                        </button>
                                    </div>

                                    <div id="post_take_buttons" style="display:none">
                                        <button type="button" class="btn btn-warning mt-3" onClick="cancel_preview()">
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
                                    <div class="form-text text-danger text-md mb-3"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php $__errorArgs = ['galery'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="form-text text-danger text-md mb-3"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Rekomendasi</label>
                            <textarea name="recomendation" id="recomendation" oninput="autoGrowRecomendation(this)"
                                class="form-control <?php $__errorArgs = ['recomendation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ketik disini ..." required><?php echo e(old('recomendation')); ?></textarea>
                            <?php $__errorArgs = ['recomendation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-text text-danger text-md mb-3"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold" for="leader">Atasan langsung</label>
                            <input type="text" class="form-control" disabled
                                value="<?php echo e($leader?->name); ?> <?php echo e($leader ? '(' . $leader->nip . ')' : ''); ?>">
                            <input type="hidden" name="leader" value="<?php echo e($leader->id); ?>">
                            <?php $__errorArgs = ['leader'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger form-text text-md"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                </div>





                    <button type="button" class="btn btn-success btn-lg rounded-pill w-100" data-bs-toggle="modal"
                        data-bs-target="#modalSave">SIMPAN</button>

                    <div class="modal fade" id="modalSave" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Safety Comitee</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Simpan data safety comitee?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-success">Ya</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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

        function autoGrowRecomendation(element) {
            element.style.height = "5px";
            element.style.height = (element.scrollHeight) + "px";
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Safety Comitee Form'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse_laravel\resources\views/qrp/qrp-form.blade.php ENDPATH**/ ?>