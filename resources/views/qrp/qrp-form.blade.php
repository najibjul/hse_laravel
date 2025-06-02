@extends('layouts.app', ['title' => 'Safety Comitee Form'])
@section('content')
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
                        <li class="breadcrumb-item"><a href="{{ route('qrp.daily-checking') }}">Daily Checking</a></li>
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
            <form method="POST" action="{{ route('qrp.qrp-form-post') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Faktor temuan</label>
                            <input type="text" class="form-control" disabled value="{{ $factor->factor_name }}">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Area temuan</label>
                            <input name="area" type="text" class="form-control @error('area') is-invalid @enderror"
                                placeholder="ketik disini ..." value="{{ old('area') }}" required>
                            @error('area')
                                <div class="form-text text-danger text-md mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Deskripsi temuan</label>
                            <textarea name="description" id="description" oninput="autoGrowDescription(this)"
                                class="form-control @error('description') is-invalid @enderror" placeholder="ketik disini ..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="form-text text-danger text-md mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category" class="form-control" required>
                                <option value="">--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="form-text text-danger text-md mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Rank</label>
                            @foreach ($ranks as $rank)
                                <div class="form-check mb-3 d-flex gap-3">
                                    <input required type="radio" class="form-check-input" name="rank"
                                        id="rank{{ $rank->id }}" value="{{ $rank->id }}"
                                        {{ old('rank') == $rank->id ? 'checked' : '' }}>
                                    <label for="rank{{ $rank->id }}"
                                        class="form-check-label fw-bold">{{ $rank->rank_name }}.
                                    </label>
                                    <label for="rank{{ $rank->id }}"
                                        class="form-check-label">{{ $rank->rank_description }}</label>
                                </div>
                            @endforeach
                            @error('rank')
                                <div class="form-text text-danger text-md mb-3">{{ $message }}</div>
                            @enderror
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
                                        value="{{ old('galery') }}">
                                </div>

                                @error('dataUri')
                                    <div class="form-text text-danger text-md mb-3">{{ $message }}</div>
                                @enderror
                                @error('galery')
                                    <div class="form-text text-danger text-md mb-3">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold">Rekomendasi</label>
                            <textarea name="recomendation" id="recomendation" oninput="autoGrowRecomendation(this)"
                                class="form-control @error('recomendation') is-invalid @enderror" placeholder="ketik disini ..." required>{{ old('recomendation') }}</textarea>
                            @error('recomendation')
                                <div class="form-text text-danger text-md mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group mb-5">
                            <label class="form-label fw-bold" for="leader">Atasan langsung</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $leader?->name }} {{ $leader ? '(' . $leader->nip . ')' : '' }}">
                            <input type="text" name="leader" class="d-none" value="{{ $leader->id }}">
                            @error('leader')
                                <div class="text-danger form-text text-md">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Asst. Dept. Head</label>
                            <select id="adh" class="form-control @error('adh') is-invalid @enderror" name="adh">
                            </select>
                            @error('adh')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

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
@endsection

@push('scripts')
    <script src="{{ asset('assets/webcam/webcam.min.js') }}" defer></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {


            Webcam.on('error', function(err) {
                $('#fotoLangsung').addClass('d-none');
                $('#galeri-tab').addClass('active');
                $('#direct').removeClass('show active');
                $('#galeri').addClass('show active');
            });


            @if ($agent->isMobile())
                Webcam.set({
                    width: 240,
                    height: 320,
                    image_format: 'jpeg',
                    jpeg_quality: 90,
                    constraints: {
                        facingMode: "environment"
                    }
                });
            @else
                Webcam.set({
                    width: 320,
                    height: 240,
                    image_format: 'jpeg',
                    jpeg_quality: 90,
                    constraints: {
                        facingMode: "environment"
                    }
                });
            @endif
            Webcam.attach('#my_camera');

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
@endpush
