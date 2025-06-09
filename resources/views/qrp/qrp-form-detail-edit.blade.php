@extends('layouts.app', ['title' => 'Edit QRP'])
@section('content')
    @if (!$agent->isMobile())
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('qrp.daily-checking') }}">Daily Checking</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('qrp.qrp-form-detail', encrypt($dailyCheck->id)) }}">Detail</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card ">
        <div class="card-header">
            <h4>Edit</h4>
        </div>
        <form method="POST" action="{{ route('qrp.qrp-form-update', $dailyCheck->id) }}" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-4">
                        <label class="form-label fw-bold">Faktor temuan</label>
                        <select name="factor" class="form-control" required>
                            @foreach ($factors as $factor)
                                <option value="{{ $factor->id }}"
                                    {{ $factor->id == $dailyCheck->factor_id ? 'selected' : '' }}>
                                    {{ $factor->factor_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <label class="form-label fw-bold">Area temuan</label>
                        <input name="area" type="text" class="form-control @error('area') is-invalid @enderror"
                            placeholder="ketik disini" value="{{ $dailyCheck->area }}" required>
                        @error('area')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <label class="form-label fw-bold">Deskripsi temuan</label>
                        <textarea name="description" id="description" oninput="autoGrowDescription(this)"
                            class="form-control @error('description') is-invalid @enderror" placeholder="ketik disini" required>{{ $dailyCheck->qrpDetail->description }}</textarea>
                        @error('description')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category" class="form-control" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $category->id == $dailyCheck->qrpDetail->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <label class="form-label fw-bold">Gambar temuan</label>
                        <div class="my-2">
                            <img src="{{ asset('storage/image/' . $dailyCheck->qrpDetail->before) }}"
                                alt="{{ $dailyCheck->qrpDetail->before }}"
                                class="img-fluid @if ($agent->isDesktop()) w-50 @endif">
                        </div>
                        <input type="text" name="dataUri" id="dataUri" hidden>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation" id="fotoLangsung">
                                <button class="nav-link active" id="direct-tab" data-bs-toggle="tab"
                                    data-bs-target="#direct" type="button" role="tab" aria-controls="direct"
                                    aria-selected="true">Foto
                                    langsung</button>
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
                            <div class="tab-pane fade my-3" id="galeri" role="tabpanel" aria-labelledby="galeri-tab">
                                <input type="file" class="form-control" name="galery" value="{{ old('galery') }}">
                            </div>

                            @error('dataUri')
                                <div class="form-text text-danger mb-3">{{ $message }}</div>
                            @enderror
                            @error('galery')
                                <div class="form-text text-danger mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <label class="form-label fw-bold">Rekomendasi</label>
                        <textarea name="recomendation" id="recomendation" oninput="autoGrowRecomendation(this)"
                            class="form-control @error('recomendation') is-invalid @enderror" placeholder="ketik disini" required>@foreach (json_decode($dailyCheck->qrpDetail->recomendation, true) as $item){!! $item['recomendation'] !!}@endforeach</textarea>
                        @error('recomendation')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Asst. Dept. Head</label>
                            <select id="adh" class="form-control @error('adh') is-invalid @enderror required"
                                name="adh">
                                @foreach ($adhs as $adh)
                                    <option value="{{ $adh->id }}"
                                        {{ $dailyCheck->qrpDetail->adh_id == $adh->id ? 'selected' : '' }}>
                                        {{ $adh->name }} ({{ $adh->nip }})</option>
                                @endforeach
                            </select>
                            @error('adh')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
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
@endsection

@push('scripts')
    <script src="{{ asset('assets/webcam/webcam.min.js') }}" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            Webcam.attach('#my_camera');

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
                });
            @endif
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
    </script>
@endpush
