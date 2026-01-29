<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>FOTO TINDAK LANJUT</h5>
                </div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active w-50" id="nav-live-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-live" type="button" role="tab" aria-controls="nav-live"
                                aria-selected="true">Foto langsung</button>
                            <button class="nav-link w-50" id="nav-galery-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-galery" type="button" role="tab" aria-controls="nav-galery"
                                aria-selected="false">Ambil dari galeri</button>
                        </div>
                    </nav>

                    <div class="tab-content py-4 px-3" id="nav-tabContent">
                        <div class="tab-pane fade show active text-center" id="nav-live" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            
                            <div class="d-flex justify-content-center align-item-center pt-3" >
                                <div id="my_camera"></div>
                            </div>

                            <div id="pre_take_buttons">
                                <div class="d-block d-md-none">
                                    <div class="mb-5"></div>
                                </div>
                                <button type="button" class="btn btn-outline-success mt-3" onClick="preview_snapshot()">
                                    <i class="ti ti-camera"></i> Ambil gambar
                                </button>
                            </div>

                            <div id="post_take_buttons" style="display:none">
                                <button type="button" class="btn btn-outline-warning mt-3" onClick="cancel_preview()">
                                    <i class="ti ti-arrow-back-up"></i> Ambil ulang gambar
                                </button>
                            </div>

                            <form method="POST" action="<?php echo e(route('qrp.tindak-lanjut-live', $dailyCheck->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('put'); ?>
                                <input type="text" name="dataUri" id="dataUri" hidden>
                                <button type="submit" id="btn-live-save" class="btn btn-success btn-lg mt-5 w-100 rounded-pill d-none">SIMPAN FOTO</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="nav-galery" role="tabpanel" aria-labelledby="nav-galery-tab">
                            <form method="POST" action="<?php echo e(route('qrp.tindak-lanjut-gallery', $dailyCheck->id)); ?>" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('put'); ?>
                                <input type="file" name="image" class="form-control">
                                <div class="text-warning">Maksimal 5 MB, gambar (png, jpg, jpeg)</div>
                                <button type="submit" class="btn btn-success btn-lg mt-4 w-100 rounded-pill">SIMPAN FOTO</button>
                            </form>
                        </div>
                    </div>

                    

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/webcam/webcam.min.js')); ?>" defer></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Webcam.on('error', function(err) {
                $('#nav-live-tab').addClass('d-none');
                $('#nav-galery-tab').addClass('active');
                $('#nav-live').removeClass('show active');
                $('#nav-galery').addClass('show active');
            });

            Webcam.set({
                <?php if($agent->isMobile()): ?>
                    width: 240,
                    height: 320,
                <?php else: ?>
                    width: 320,
                    height: 240,
                <?php endif; ?>
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
                        if (device.kind === 'videoinput') {}
                    });
                });
        });

        let btnLiveSave = document.getElementById('btn-live-save');

        function preview_snapshot() {
            Webcam.snap(function(data_uri) {
                document.getElementById('dataUri').value = data_uri;
            });
            Webcam.freeze();
            document.getElementById('pre_take_buttons').style.display = 'none';
            document.getElementById('post_take_buttons').style.display = '';

            btnLiveSave.classList.remove('d-none');
            btnLiveSave.classList.add('d-block');
        }

        function cancel_preview() {
            document.getElementById('dataUri').value = "";
            Webcam.unfreeze();
            document.getElementById('pre_take_buttons').style.display = '';
            document.getElementById('post_take_buttons').style.display = 'none';
            
            btnLiveSave.classList.remove('d-block');
            btnLiveSave.classList.add('d-none');
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
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
    <style type="text/css">
        #results {
            float: right;
            margin: 20px;
            padding: 20px;
            border: 1px solid;
            background: #ccc;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Foto tindak lanjut'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\hse\resources\views/qrp/tindak-lanjut.blade.php ENDPATH**/ ?>