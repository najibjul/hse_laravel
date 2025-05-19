@extends('layouts.app')
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Detail</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quick Risk Prediction</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('qrp.daily-checking') }}">Daily Checking</a></li>
                        <li class="breadcrumb-item" aria-current="page">Detail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Detail</h4>
                <h4 class="mt-1">
                    <span class="{{ $dailyCheck->qrpDetail->qrpStatus->class }}">{{ $dailyCheck->qrpDetail->qrpStatus->name }}</span>
                </h4>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group ">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label">Kategori temuan</label>
                        <input type="text" class="form-control" disabled
                            value="{{ strtoupper($dailyCheck->checking_category) }}">
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label">Area temuan</label>
                        <input name="area" type="text" class="form-control" disabled placeholder="ketik disini"
                            value="{{ $dailyCheck->area }}">
                    </div>
                </div>
            </div>
            <div class="form-group ">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label">Deskripsi temuan</label>
                        <textarea name="description" class="form-control" disabled oninput="autoGrowDescription(this)" id="description"
                            placeholder="ketik disini">{{ $dailyCheck->qrpDetail->description }}</textarea>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label">Rekomendasi</label>
                        <textarea name="description" class="form-control" disabled id="recomendation" placeholder="ketik disini"
                            oninput="autoGrowRecomendation(this)">{{ $dailyCheck->qrpDetail->recomendation }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label">Temuan sebelum diaction</label>
                        <img src="{{ asset('storage/image/' . $dailyCheck->qrpDetail->before) }}" class="img-thumbnail"
                            alt="{{ $dailyCheck->qrpDetail->before }}">
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label">Temuan sesudah diaction</label>
                        @if($dailyCheck->qrpDetail->after)
                            <img src="{{ asset('storage/image/' . $dailyCheck->qrpDetail->after) }}" class="img-thumbnail"
                                alt="{{ $dailyCheck->qrpDetail->after }}">

                            <label class="form-label">Tgl. upload : {{ $dailyCheck->qrpDetail->after_uploaded_at }}</label>
                        @else
                            @if (!$dailyCheck->qrpDetail->dept_head_approved_at)
                                <h5 class="mt-1">
                                    <span class="badge bg-warning text-white">BELUM ADA ACTION</span>
                                </h5>
                            @elseif ($dailyCheck->qrpDetail->dept_head_approved_at and $dailyCheck->user_id == auth()->user()->id)

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
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
                                    <div class="tab-pane fade show active my-3" id="direct" role="tabpanel"
                                        aria-labelledby="direct-tab">
                                        <div id="my_camera"></div>
                                        <form action="{{ route('qrp.upload-close', $dailyCheck->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="text" name="dataUri" id="dataUri" hidden>

                                            <div id="pre_take_buttons">
                                                <button type="button" class="btn btn-success mt-3" onClick="preview_snapshot()">
                                                    <i class="ti ti-camera"></i> Ambil gambar
                                                </button>
                                            </div>

                                            <div id="post_take_buttons" style="display:none">
                                                <button type="button" class="btn btn-warning mt-3" onClick="cancel_preview()">
                                                    <i class="ti ti-arrow-back-up"></i> Ambil ulang gambar
                                                </button>
                                                <div>
                                                    <button class="btn btn-success mt-3"><i class="ti ti-device-floppy"></i> Simpan
                                                        gambar penyelesaian</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade my-3" id="galeri" role="tabpanel" aria-labelledby="galeri-tab">
                                    <form action="{{ route('qrp.upload-close-galery', $dailyCheck->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                        <input type="file" required class="form-control @error('galery') is-invalid @enderror" name="galery">
                                        <button type="submit" class="btn btn-success mt-3">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        @error('dataUri')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                        @enderror
                        @error('galery')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                        @enderror

                    </div>
                </div>
            </div>
            <div class="form-group ">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label">Dept. Head</label>
                        <input class="form-control" disabled placeholder="ketik disini"
                            value="{{ $dailyCheck->qrpDetail->deptHead->name }} ({{ $dailyCheck->qrpDetail->deptHead->nip }})">
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label">Tanggal DH konfirmasi</label>
                        <input class="form-control" disabled
                            value="{{ $dailyCheck->qrpDetail->dept_head_approved_at ? \Carbon\Carbon::parse($dailyCheck->qrpDetail->dept_head_approved_at)->format('d M Y H:i') : '' }}">
                    </div>
                </div>
            </div>

            @if (auth()->user()->deptHead and auth()->user()->department_id == $dailyCheck->user->department_id and !$dailyCheck->qrpDetail->dept_head_approved_at and $dailyCheck->qrpDetail->qrp_status_id != 3)
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cancel">CANCEL</button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationModal">KONFIRMASI <i
                                class="ti ti-chevron-right"></i></button>
                    </div>

                    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Lanjut perbaikan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('qrp.dh-approval', $dailyCheck->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="form-label">Tambah rekomendasi</label>
                                            <textarea name="recomendation" required
                                                class="form-control @error('recomendation') is-invalid @enderror"
                                                placeholder="ketik disini">{{ $dailyCheck->qrpDetail->recomendation }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-success">Konfirmasi</button>
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
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('qrp.dh-cancel', $dailyCheck->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        Berikan rekomendasi & alasan?
                                        <textarea name="recomendation" class="form-control @error('recomendation') is-invalid @enderror ">{{ $dailyCheck->qrpDetail->recomendation }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-warning">Ya</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->user()->deptHead and auth()->user()->department_id == $dailyCheck->user->department_id and $dailyCheck->qrpDetail->qrp_status_id == 4)
            <div class="form-group mb-4">
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#open">TOLAK OPEN</button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#close">CLOSE QRP<i
                                class="ti ti-chevron-right"></i></button>
                    </div>

                    <div class="modal fade" id="open" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">TOLAK OPEN</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('qrp.tolak-open', $dailyCheck->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="form-label">Alasan & rekomendasi ulang</label>
                                            <textarea name="recomendation" id="recomendation" required
                                                class="form-control @error('recomendation') is-invalid @enderror"
                                                placeholder="ketik disini">{{ $dailyCheck->qrpDetail->recomendation }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-warning">Tolak open</button>
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
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('qrp.close', $dailyCheck->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        CLose laporan QRP?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-success">Ya</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/webcam/webcam.min.js') }}"></script>

    <script>
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        if (document.getElementById('my_camera')) {
            Webcam.attach('#my_camera');
        }
    </script>

    <script>
        function preview_snapshot() {

            Webcam.snap(function (data_uri) {
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
            Webcam.snap(function (data_uri) {
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

        document.addEventListener("DOMContentLoaded", function () {
            const recomendation = document.getElementById("recomendation");
            const description = document.getElementById("description");
            
            autoGrowDescription(description);
            autoGrowRecomendation(recomendation);

            @if ($errors->any())
                let errorList = '';
                @foreach ($errors->all() as $error)
                    errorList += `• {{ $error }}\n`;
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    text: 'Silakan periksa form Anda.',
                    footer: `<pre style="text-align: left;">${errorList}</pre>`,
                    confirmButtonColor: "#dc3545"
                });
            @endif

            @if(session()->has('success'))
                Swal.fire({
                    title: "Berhasil",
                    text: `{{ session('success') }}`,
                    icon: "success",
                    confirmButtonColor: "#198754",
                });
            @endif

            @if(session()->has('error'))
                Swal.fire({
                    title: "Berhasil",
                    text: `{{ session('error') }}`,
                    icon: "error",
                    confirmButtonColor: "#dc3545"
                });
            @endif
        });
    </script>
@endpush

@push('styles')
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
@endpush