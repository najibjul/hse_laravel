@extends('layouts.app', ['title' => 'QRP Form'])
@section('content')
    <div class="page-header d-lg-block d-none">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">QRP Form</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quick Risk Prediction</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('qrp.daily-checking') }}">Daily Checking</a></li>
                        <li class="breadcrumb-item" aria-current="page">QRP Form</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card ">
        <div class="card-header">
            <h4>QRP Form</h4>
        </div>
        <form method="POST" action="{{ route('qrp.qrp-form-post') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Faktor temuan</label>
                            <input type="text" class="form-control" disabled value="{{ $factor->factor_name }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Area temuan</label>
                            <input name="area" type="text" class="form-control @error('area') is-invalid @enderror"
                                placeholder="ketik disini" value="{{ old('area') }}" required>
                            @error('area')
                                <div class="form-text text-danger mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Deskripsi temuan</label>
                            <textarea name="description" id="description" oninput="autoGrowDescription(this)"
                            class="form-control @error('description') is-invalid @enderror" placeholder="ketik disini" required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-control">
                                <option value="">--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Rank</label>
                            @foreach ($ranks as $rank)
                                <div class="form-check mb-4">
                                    <input type="radio" class="form-check-input" name="rank" id="rank{{ $rank->id }}" value="{{ $rank->id }}">
                                    <label for="rank{{ $rank->id }}" class="form-check-label">{{ $rank->rank_name }}</label>
                                    <label for="rank{{ $rank->id }}" class="form-check-label">{{ $rank->rank_description }}</label>
                                </div>
                            @endforeach
                            @error('rank')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Gambar temuan</label>
                            <input type="text" name="dataUri" id="dataUri" hidden>

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
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Rekomendasi</label>
                            <textarea name="recomendation" id="recomendation" oninput="autoGrowRecomendation(this)"
                            class="form-control @error('recomendation') is-invalid @enderror" placeholder="ketik disini" required>{{ old('recomendation') }}</textarea>
                            @error('recomendation')
                            <div class="form-text text-danger mb-3">{{ $message }}</div>
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




                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                    data-bs-target="#modalSave">Simpan</button>

                <div class="modal fade" id="modalSave" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">QRP Form</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Simpan data QRP?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-success">Ya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('select2.min.js') }}"></script>
    <script src="{{ asset('assets/webcam/webcam.min.js') }}"></script>

    <script>
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
    </script>

    <script>
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

        document.addEventListener("DOMContentLoaded", function() {
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

            let url = "{{ route('qrp.qrp-form.search-adh') }}"

            $('#adh').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari Asst. Dept. Head...',
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.name + ' (' + item.nip + ')',
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('select2-bootstrap-5-theme.min.css') }}">
@endpush
