@extends('layouts.app', ['title' => 'Detail'])
@section('content')
    @if (!$agent->isMobile())
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Detail</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Safety Comitee</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('qrp.daily-checking') }}">Daily Checking</a></li>
                            <li class="breadcrumb-item" aria-current="page">Detail</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>Detail</h4>
        </div>
        <div class="card-body">
            <h4 class="mt-1 text-end">
                <span
                    class="{{ $dailyCheck->qrpDetail->qrpStatus->class }}">{{ $dailyCheck->qrpDetail->qrpStatus->name }}</span>
            </h4>
            @if ($dailyCheck->qrpDetail->closed_at)
                <div class="text-end">
                    <label class="form-label">Close :
                        {{ \Carbon\Carbon::parse($dailyCheck->qrpDetail->closed_at)->translatedFormat('d M Y H:i:s') }}</label>
                </div>
            @endif
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">User</label>
                    <input type="text" class="form-control" disabled
                        value="{{ $dailyCheck->user->name }} {{ '(' . $dailyCheck->user->nip . ')' }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Tanggal temuan</label>
                    <input type="text" class="form-control" disabled
                        value="{{ \Carbon\Carbon::parse($dailyCheck->created_at)->translatedFormat('d M Y H:i') }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Departemen</label>
                    <input type="text" class="form-control" disabled
                        value="{{ $dailyCheck->qrpDetail->department->department_name }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Faktor temuan</label>
                    <input type="text" class="form-control" disabled
                        value="{{ strtoupper($dailyCheck->factor->factor_name) }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Area temuan</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="{{ $dailyCheck->area }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Deskripsi temuan</label>
                    <textarea class="form-control" disabled oninput="autoGrowDescription(this)" id="description" placeholder="ketik disini">{{ $dailyCheck->qrpDetail->description }}</textarea>
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Kategori</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="{{ $dailyCheck->qrpDetail->category->category_name }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Rank</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="{{ $dailyCheck->qrpDetail->rank->rank_name }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Rekomendasi</label>
                    <br>
                    @foreach (json_decode($dailyCheck->qrpDetail->recomendation, true) as $recomendation)
                        <label for="label-form" class="">
                            • {!! $recomendation['user'] !!}
                        </label>
                        <div class="font-italic text-secondary mb-4">
                            <i>
                                {!! nl2br($recomendation['recomendation']) !!}
                            </i>
                        </div>
                        
                    @endforeach
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Batas perbaikan</label>
                    <input class="form-control" disabled
                        value="{{ \Carbon\Carbon::parse($dailyCheck->qrpDetail->due_date)->format('d M Y') }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Temuan sebelum diaction</label>
                    <img src="{{ asset('storage/image/' . $dailyCheck->qrpDetail->before) }}" class="img-thumbnail"
                        alt="{{ $dailyCheck->qrpDetail->before }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Temuan sesudah diaction</label>
                    @if ($dailyCheck->qrpDetail->after)
                        <img src="{{ asset('storage/image/' . $dailyCheck->qrpDetail->after) }}" class="img-thumbnail"
                            alt="{{ $dailyCheck->qrpDetail->after }}">

                        <label class="form-label">Tgl. upload : {{ $dailyCheck->qrpDetail->after_uploaded_at }}</label>

                        @if ($dailyCheck->user_id == auth()->user()->id && $dailyCheck->qrpDetail->qrp_status_id == 4)
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
                                    <form action="{{ route('qrp.upload-close-edit', $dailyCheck->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="text" name="dataUri" id="dataUri" hidden>

                                        <div id="pre_take_buttons">
                                            <button type="button" class="btn btn-success mt-3"
                                                onClick="preview_snapshot()">
                                                <i class="ti ti-camera"></i> Ambil gambar
                                            </button>
                                        </div>

                                        <div id="post_take_buttons" style="display:none">
                                            <button type="button" class="btn  btn-warning mt-3"
                                                onClick="cancel_preview()">
                                                <i class="ti ti-arrow-back-up"></i> Ambil ulang gambar
                                            </button>
                                            <div>
                                                <button class="btn btn-warning mt-3 "><i class="ti ti-device-floppy "></i>
                                                    Update
                                                    gambar penyelesaian</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade my-3" id="galeri" role="tabpanel"
                                    aria-labelledby="galeri-tab">
                                    <form action="{{ route('qrp.upload-close-galery-edit', $dailyCheck->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" required
                                            class="form-control  @error('galery') is-invalid @enderror" name="galery">
                                        <button type="submit" class="btn btn-warning mt-3 ">Update</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        @if (!$dailyCheck->qrpDetail->adh_approve_date or !$dailyCheck->qrpDetail->dh_approve_date or !$dailyCheck->qrpDetail->ph_approve_date)
                            <h5 class="mt-1">
                                <span class="badge bg-warning text-white">BELUM ADA ACTION</span>
                            </h5>

                            @if (
                                $dailyCheck->qrpDetail->adh_approve_date and
                                    $dailyCheck->user_id == auth()->user()->id and
                                    !$dailyCheck->qrpDetail->dh_id or
                                    $dailyCheck->qrpDetail->dh_approve_date and
                                        $dailyCheck->user_id == auth()->user()->id and
                                        !$dailyCheck->qrpDetail->ph_id or $dailyCheck->qrpDetail->ph_approve_date and
                                        $dailyCheck->user_id == auth()->user()->id
                                        )
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
                                        <form action="{{ route('qrp.upload-close', $dailyCheck->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
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
                                        <form action="{{ route('qrp.upload-close-galery', $dailyCheck->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" required
                                                class="form-control @error('galery') is-invalid @enderror" name="galery">
                                            <button type="submit" class="btn btn-success mt-3">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif

                    @error('dataUri')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror
                    @error('galery')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">Asst. Dept. Head</label>
                    <input type="text" class="form-control" disabled placeholder="ketik disini"
                        value="{{ $dailyCheck->qrpDetail->adh->name }} {{ '(' . $dailyCheck->qrpDetail->adh->nip . ')' }}">

                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <label class="form-label fw-bold">ADH konfirmasi</label>
                    <input class="form-control" disabled
                        value="{{ $dailyCheck->qrpDetail->adh_approve_date ? \Carbon\Carbon::parse($dailyCheck->qrpDetail->adh_approve_date)->format('d M Y H:i') : '' }}">
                </div>
                @if ($dailyCheck->qrpDetail->dh_id)
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label fw-bold">Dept. Head</label>
                        <input type="text" class="form-control" disabled placeholder="ketik disini"
                            value="{{ $dailyCheck->qrpDetail->dh->name }} {{ '(' . $dailyCheck->qrpDetail->dh->nip . ')' }}">

                    </div>
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label fw-bold">DH konfirmasi</label>
                        <input class="form-control" disabled
                            value="{{ $dailyCheck->qrpDetail->dh_approve_date ? \Carbon\Carbon::parse($dailyCheck->qrpDetail->dh_approve_date)->format('d M Y H:i') : '' }}">
                    </div>
                @endif
                @if ($dailyCheck->qrpDetail->ph_id)
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label fw-bold">Plant Head</label>
                        <input type="text" class="form-control" disabled placeholder="ketik disini"
                            value="{{ $dailyCheck->qrpDetail->ph->name }} {{ '(' . $dailyCheck->qrpDetail->ph->nip . ')' }}">

                    </div>
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <label class="form-label fw-bold">PH konfirmasi</label>
                        <input class="form-control" disabled
                            value="{{ $dailyCheck->qrpDetail->ph_approve_date ? \Carbon\Carbon::parse($dailyCheck->qrpDetail->ph_approve_date)->format('d M Y H:i') : '' }}">
                    </div>
                @endif

            </div>

            @if (auth()->user()->id == $dailyCheck->user_id && $dailyCheck->qrpDetail->qrp_status_id == 1)
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('qrp.qrp-form-detail.edit', encrypt($dailyCheck->id)) }}"
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
                            <form action="{{ route('qrp.qrp-form-detail.destroy', $dailyCheck->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
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
            @endif


            @if (
                $dailyCheck->qrpDetail->qrp_status_id == 1 and
                    $dailyCheck->qrpDetail->adh_id == auth()->user()->id and
                    $dailyCheck->qrpDetail->dh_id == null or
                    $dailyCheck->qrpDetail->qrp_status_id == 1 and
                        $dailyCheck->qrpDetail->dh_id == auth()->user()->id and
                        $dailyCheck->qrpDetail->ph_id == null or 
                        $dailyCheck->qrpDetail->qrp_status_id == 1 and
                        $dailyCheck->qrpDetail->ph_id == auth()->user()->id
                        )
                <div class="d-flex justify-content-center gap-1 mt-4">
                    {{-- <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancel">Cancel</button> --}}


                    <button class="btn btn-sm btn-success " data-bs-toggle="modal" data-bs-target="#confirm">Konfirmasi
                    </button>

                    <button class="btn btn-sm btn-info text-nowrap " data-bs-toggle="modal"
                        data-bs-target="#confirmationModal">Revisi rekomendasi </button>

                    @if (auth()->user()->position_id != 1)
                    <button class="btn btn-warning btn-sm " data-bs-toggle="modal" data-bs-target="#riseup">Rise
                        Up</button>
                        @endif

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
                            <form action="{{ route('qrp.confirm', $dailyCheck->id) }}" method="POST">
                                @csrf
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
                            <form action="{{ route('rise-up', $dailyCheck->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">

                                        @if (!$dailyCheck->qrpDetail->dh_id)
                                            <label>Pilih Dept. Head :</label>
                                            <select name="riseup" class="form-control">
                                                <option value="">--</option>
                                                @foreach ($deptHeads as $deptHead)
                                                    <option value="{{ $deptHead->id }}">{{ $deptHead->name }}
                                                        {{ '(' . $deptHead->nip . ')' }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($dailyCheck->qrpDetail->dh_id and !$dailyCheck->qrpDetail->ph_id)
                                            <label>Pilih Plant Head :</label>
                                            <select name="riseup" class="form-control">
                                                <option value="">--</option>
                                                @foreach ($plantHeads as $plantHead)
                                                    <option value="{{ $plantHead->id }}">{{ $plantHead->name }}
                                                        {{ '(' . $plantHead->nip . ')' }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Kembali</button>
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
                            <form action="{{ route('qrp.dh-cancel', $dailyCheck->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    Berikan rekomendasi & alasan?
                                    <textarea name="recomendation" class="form-control @error('recomendation') is-invalid @enderror ">{{ $dailyCheck->qrpDetail->recomendation }}</textarea>
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

            @if (
                $dailyCheck->qrpDetail->adh_id == auth()->user()->id and
                    $dailyCheck->qrpDetail->qrp_status_id == 4 and
                    !$dailyCheck->qrpDetail->dh_id or
                    $dailyCheck->qrpDetail->dh_id == auth()->user()->id and
                        $dailyCheck->qrpDetail->qrp_status_id == 4 and
                        !$dailyCheck->qrpDetail->ph_id or $dailyCheck->qrpDetail->ph_id == auth()->user()->id and
                        $dailyCheck->qrpDetail->qrp_status_id == 4)
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
@endsection

@push('scripts')
    <script src="{{ asset('jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/webcam/webcam.min.js') }}"></script>
    <script>
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

        document.addEventListener("DOMContentLoaded", function() {


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
