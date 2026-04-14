@extends('layouts.app', ['title' => 'Detail laporan'])
@section('content')
    <div class="page-header d-none d-md-block">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Detail</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Safety Comitee</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('qrp.daily-checking') }}">Daily Checking</a></li>
                        <li class="breadcrumb-item" aria-current="page">Detail laporan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="my-2 d-flex justify-content-between">

                <h4>Detail laporan</h4>

                <div class="d-none d-md-block">
                    <div class="d-flex justify-content-center gap-3">
                        @if (auth()->user()->id == $dailyCheck->user_id && $dailyCheck->qrpDetail?->qrp_status_id == 1)
                            <a href="{{ route('qrp.qrp-form-detail.edit', encrypt($dailyCheck->id)) }}"
                                class="btn btn-warning"><i class="ti ti-edit"></i> Edit Data</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete"><i
                                    class="ti ti-trash"></i> Hapus</button>
                        @endif
                        <form id="form-export" action="{{ route('qrp.show-export', $dailyCheck->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info rounded"><i class="ti ti-file-export"></i>
                                Ekspor</button>
                        </form>
                    </div>
                </div>

                <div class="d-block d-md-none">
                    <div class="dropdown">
                        <a class="btn btn-light border border-success text-success bg-white" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#" onclick="document.getElementById('form-export').submit(); return false;">Ekspor</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="{{ $dailyCheck->qrpDetail?->qrpStatus?->class }}">{{ $dailyCheck->qrpDetail?->qrpStatus?->name }}</div>
        </div>

        <div class="card-body">
            @if ($dailyCheck->qrpDetail?->closed_at)
                <div class="text-end">
                    <label class="form-label">Close :
                        {{ \Carbon\Carbon::parse($dailyCheck->qrpDetail?->closed_at)->translatedFormat('d M Y H:i:s') }}</label>
                </div>
            @endif
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">User pelapor</label>
                    <input type="text" class="form-control" disabled
                        value="{{ $dailyCheck->user->name }} {{ '(' . $dailyCheck->user->nip . ')' }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Tanggal temuan</label>
                    <input type="text" class="form-control" disabled
                        value="{{ \Carbon\Carbon::parse($dailyCheck->created_at)->translatedFormat('d M Y H:i') }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Departemen</label>
                    <input type="text" class="form-control" disabled
                        value="{{ $dailyCheck->department->department_name }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Faktor temuan</label>
                    <input type="text" class="form-control" disabled
                        value="{{ strtoupper($dailyCheck->factor->factor_name) }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Area temuan</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="{{ $dailyCheck->area }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Deskripsi temuan</label>
                    <textarea class="form-control" disabled oninput="autoGrowDescription(this)" id="description" placeholder="ketik disini">{{ $dailyCheck->qrpDetail?->description }}</textarea>
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Kategori</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="{{ $dailyCheck->qrpDetail?->category->category_name }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Rank</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="{{ $dailyCheck->qrpDetail?->rank->rank_name }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Rekomendasi</label>
                    <br>
                    <ul>
                        @foreach ($dailyCheck->qrpDetail?->qrpRecomendations->sortByDesc('id') as $qrpRecomendation)
                            <li>
                                <div style="text-align: justify;" class="text-secondary">
                                    {{ $qrpRecomendation->user->name }}
                                    ({{ $qrpRecomendation->user->nip }})
                                </div>
                                <div class="">
                                    {{ $qrpRecomendation->recomendation }}
                                </div>
                                <div style="font-size: 10px;" class="">
                                    <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($qrpRecomendation->created_at)->translatedFormat('d M Y H:i') }}
                                </div>
                                <br>
                            </li>
                        @endforeach
                    </ul>

                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Batas perbaikan</label>
                    <input class="form-control" disabled
                        value="{{ \Carbon\Carbon::parse($dailyCheck->qrpDetail?->due_date)->format('d M Y') }}">
                        @if ($dailyCheck->qrpDetail?->revision_note)
                        <div class="mt-5">
                            <label class="form-label fw-bold">Note perubahan batas perbaikan</label>
                            <div>

                                <i class="text-secondary">
                                    {{ $dailyCheck->qrpDetail?->revision_note }}
                                </i>
                            </div>
                        </div>
                        @endif
                </div>

                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Approval</label>
                    <ul>
                        @foreach ($dailyCheck->qrpDetail?->qrpApprovals->sortByDesc('id') as $qrpApproval)
                            <li class="d-flex justify-content-between  mb-3">
                                <div>
                                    <div>
                                        {{ $qrpApproval->approval->name }} ({{ $qrpApproval->approval->nip }})
                                    </div>
                                    <div style="font-size: 10px;" class="text-secondary">
                                        {{ $qrpApproval->created_at->translatedFormat('d M Y H:i') }}
                                    </div>
                                </div>
                                <div>

                                    <div class="rounded {{ $qrpApproval->status == 'approved' || $qrpApproval->status == 'waiting' ? 'badge bg-success' : 'badge bg-warning' }}">{{ $qrpApproval->status == 'approved' || $qrpApproval->status == 'waiting' ? 'approver' : $qrpApproval->status }}</div>
                                </div>
                                </li>
                                @endforeach
                    </ul>
                </div>



            </div>

            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Temuan sebelum diaction</label>
                    <div>
                        <img src="{{ asset('storage/image/' . $dailyCheck->qrpDetail?->before) }}" class="img-thumbnail"
                            alt="{{ $dailyCheck->qrpDetail?->before }}">
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 mb-5">
                    <label class="form-label fw-bold">Temuan sesudah diaction</label>
                    @if ($dailyCheck->qrpDetail?->after)
                        <div>
                            <img src="{{ asset('storage/image/' . $dailyCheck->qrpDetail?->after) }}"
                                class="img-thumbnail" alt="{{ $dailyCheck->qrpDetail?->after }}">
                        </div>

                        <label class="form-label">Tgl. upload :
                            {{ $dailyCheck->qrpDetail?->after_uploaded_at }}</label>
                    @else
                        {{-- @if ($dailyCheck->qrpDetail?->qrpApprovals->sortByDesc('id')->first()->approved_at) --}}
                        <h5 class="mt-1">
                            <span class="badge bg-warning text-white">BELUM ADA ACTION</span>
                        </h5>
                        {{-- @endif --}}
                    @endif

                    @error('dataUri')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror
                    @error('galery')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                </div>
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
                        <form action="{{ route('qrp.qrp-form-detail.destroy', $dailyCheck->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                Hapus data safety comitee?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-danger">Ya</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if (
                $dailyCheck->qrpDetail?->qrp_status_id == 1 &&
                    $dailyCheck->qrpDetail?->qrpApprovals->sortByDesc('id')->first()->approval_id == auth()->user()->id)
                <div class="modal fade" id="confirm" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Lanjut perbaikan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('qrp.confirm', $dailyCheck->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    Lanjut perbaikan sesuai rekomendasi?
                                    {{-- <div class="d-none" id="due-date-confirm-content">
                                        <hr>
                                        <div class="my-2">
                                            <label for="due-date-confirm">Revisi tanggal</label>
                                            <input type="date" name="due_date_confirm" class="form-control" id="due-date-confirm" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                        </div>
                                        <div class="my-2">
                                            <label for="due-date-confirm-note">Catatan revisi tanggal</label>
                                            <textarea placeholder="Ketik disini ..." name="due_date_confirm_note" id="due-date-confirm-note" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    {{-- <button id="btn-due-date-confirm" type="button" class="btn btn-info" onclick="dueDateConfirm();">Ganti due date</button> --}}
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
                            <form action="{{ route('rise-up', $dailyCheck->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div>Rise up ke <b><i>{{ auth()->user()->leader?->name }}
                                                    ({{ auth()->user()->leader?->nip }})</i></b>?</div>
                                    </div>
                                    <div class="d-none" id="due-date-rise-content">
                                        <hr>
                                        <div class="my-2">
                                            <label for="due-date-rise">Revisi tanggal</label>
                                            <input type="date" name="due_date_rise" class="form-control" id="due-date-rise" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                        </div>
                                        <div class="my-2">
                                            <label for="due-date-rise-note">Catatan revisi tanggal</label>
                                            <textarea placeholder="Ketik disini ..." name="due_date_rise_note" id="due-date-rise-note" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    {{-- <button id="btn-due-date-rise" type="button" class="btn btn-info" onclick="dueDateRise();">Ganti due date</button> --}}
                                    <input type="text" name="riseup" class="d-none"
                                        value="{{ auth()->user()->leader_id }}">
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
                            <form action="{{ route('qrp.approval', $dailyCheck->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">Revisi rekomendasi</label>
                                        <textarea name="recomendation" required class="form-control @error('recomendation') is-invalid @enderror"
                                            placeholder="ketik disini"></textarea>
                                    </div>

                                    <div class="d-none" id="due-date-rev-content">
                                        <hr>
                                        <div class="my-2">
                                            <label for="due-date-rev">Revisi tanggal</label>
                                            <input type="date" name="due_date_rev" class="form-control" id="due-date-rev" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                        </div>
                                        <div class="my-2">
                                            <label for="due-date-rev-note">Catatan revisi tanggal</label>
                                            <textarea placeholder="Ketik disini ..." name="due_date_rev_note" id="due-date-rev-note" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
                                    <button id="btn-due-date-rev" type="button" class="btn btn-success" onclick="dueDateRev();">Ganti due date</button>
                                    <button type="submit" class="btn btn-info">Simpan</button>
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
                            <form action="{{ route('qrp.dh-cancel', $dailyCheck->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    Berikan rekomendasi & alasan?
                                    <textarea name="recomendation" class="form-control @error('recomendation') is-invalid @enderror ">{{ $dailyCheck->qrpDetail?->recomendation }}</textarea>
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
            @endif

            @if ($dailyCheck->qrpDetail?->qrp_status_id == 1 && $dailyCheck->qrpDetail?->qrpApprovals->sortByDesc('id')->first()->approval_id == auth()->user()->id)
            <div class="row">

                <div class="col-6 col-lg-4 mb-3">
                    <button class="btn btn-outline-info btn-lg rounded w-100 rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#confirmationModal">REVISI REKOMENDASI </button>
                </div>

                @if (auth()->user()->leader)
                <div class="col-6 col-lg-4 mb-3">
                    <button class="btn btn-outline-warning btn-lg rounded w-100 rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#riseup">RISE UP</button>
                </div>
                @endif

                <div class="col-12 col-lg-4 mb-3">

                    <button class="btn btn-success rounded btn-lg w-100 rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#confirm">KONFIRMASI
                </button>
            </div>

            </div>

            @endif

            @if (
                $dailyCheck->qrpDetail?->qrpApprovals->sortByDesc('id')->first()->approval_id == auth()->user()->id and
                    $dailyCheck->qrpDetail?->qrp_status_id == 4)
                <div class="form-group">
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-danger btn-lg w-100 rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#open">TOLAK</button>
                        <button class="btn btn-success btn-lg w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#close">CLOSE
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
                                <form action="{{ route('qrp.tolak-open', $dailyCheck->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="form-label">Alasan & rekomendasi ulang</label>
                                            <textarea name="recomendation" id="recomendation" required
                                                class="form-control @error('recomendation') is-invalid @enderror" placeholder="ketik disini"></textarea>
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
                                <form action="{{ route('qrp.close', $dailyCheck->id) }}" method="POST">
                                    @csrf
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
            @endif
        </div>



    </div>


    <div class="d-flex gap-3 mb-2">
        @if (auth()->user()->id == $dailyCheck->user_id && $dailyCheck->qrpDetail?->qrp_status_id == 1)
            <button type="button" class="btn btn-danger btn-lg rounded-pill shadow-lg w-100 d-block d-md-none" data-bs-toggle="modal"
                data-bs-target="#delete">
                HAPUS
            </button>
            <a href="{{ route('qrp.qrp-form-detail.edit', encrypt($dailyCheck->id)) }}"
                class="btn btn-warning btn-lg rounded-pill shadow-lg w-100 d-block d-md-none">
                EDIT
            </a>
        @endif
        @if (
            $dailyCheck->qrpDetail?->qrpApprovals->sortByDesc('id')->first()->approved_at and
                $dailyCheck->user_id == auth()->user()->id and ($dailyCheck->qrpDetail?->qrp_status_id == 2 or $dailyCheck->qrpDetail?->qrp_status_id == 4))
            <a href="{{ route('qrp.tindak-lanjut', encrypt($dailyCheck->id)) }}"
                class="btn btn-lg @if (!$dailyCheck->qrpDetail?->after) btn-success @else btn-warning @endif rounded-pill shadow-lg w-100">
                @if (!$dailyCheck->qrpDetail?->after)
                    FOTO TINDAK LANJUT
                @else
                    EDIT FOTO TINDAK LANJUT
                @endif
            </a>
        @endif
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
                        if (device.kind === 'videoinput') {}
                    });
                });


            const recomendation = document.getElementById("recomendation");
            const description = document.getElementById("description");

            autoGrowDescription(description);

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

        function dueDateConfirm(){
            $('#due-date-confirm-content').removeClass("d-none");
            $('#btn-due-date-confirm').addClass("d-none");
        }

        function dueDateRise(){
            $('#due-date-rise-content').removeClass("d-none");
            $('#btn-due-date-rise').addClass("d-none");
        }

        function dueDateRev(){
            $('#due-date-rev-content').removeClass("d-none");
            $('#btn-due-date-rev').addClass("d-none");
            $('#due-date-rev').prop('required', true);
            $('#due-date-rev-note').prop('required', true);
        }

        document.getElementById('confirmationModal')?.addEventListener('hidden.bs.modal', function () {
            $('#due-date-rev-content').addClass("d-none");
            $('#btn-due-date-rev').removeClass("d-none");
            $('#due-date-rev').val('').prop('required', false);
            $('#due-date-rev-note').val('').prop('required', false);
        });
    </script>
@endpush

@push('styles')
    <style type="text/css">
        #results {
            float: right;
            margin: 20px;
            padding: 20px;
            border: 1px solid;
            background: #ccc;
        }
    </style>
@endpush
