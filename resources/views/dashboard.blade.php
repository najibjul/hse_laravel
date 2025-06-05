@extends('layouts.app', ['title' => 'Dashboard'])
@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-image:url('{{ asset('assets/images/dr12.jpg') }}'); background-size: cover; background-position: center;">
        <div class="card-body">
            <h4 class="text-success">Selamat datang {{ auth()->user()->name }}</h4>
            <div class="text-muted">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</div>

            <div class="mt-4 {{ $todayChecked == 0 ? 'text-danger' : 'text-success' }}"> @if($todayChecked == 0) <i class="ti ti-alert-circle"></i> @else <i class="ti ti-circle-check"></i> @endif {{ $todayChecked == 0 ? 'Anda belum melakukan daily checking' : 'Hebat, Anda telah melakukan daily checking' }}</div>

            @if($todayChecked == 0)
                <a href="{{ route('qrp.daily-checking') }}" class="w-auto btn btn-light-success mt-3 rounded-pill">
                    Lakukan daily checking 
                    <i class="ti ti-chevron-right ms-3"></i>
                </a>
            @endif
        </div>
    </div>

    <div class="mb-3 mt-4">Summary QRP</div>
    <div class="row">
        <div class="col-6 col-xl-4">
            <div class="card border-0 border-top border-info border-3">
                <div class="card-body ">
                    <h6 class="mb-2 f-w-400 text-info">Menunggu</h6>
                    <h1 class="mb-3">{{ $waiting }}</h1>
                    <p class="mb-0 text-muted text-sm">Menunggu konfirmasi DH & penyelesaian
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="card border-0 border-top border-primary border-3">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-primary">Sedang Dikerjakan</h6>
                    <h1 class="mb-3">{{ $inProgress }}</h1>
                    <p class="mb-0 text-muted text-sm">Laporan sedang ditindak lanjuti berdasarkan rekomendasi</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="card border-0 border-top border-success border-3">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-success">Close</h6>
                    <h1 class="mb-3">{{ $close }}</h1>
                    <p class="mb-0 text-muted text-sm">Laporan telah dikonfirmasi oleh pimpinan</p>
                </div>
            </div>
        </div>
        {{-- <div class="col-6 col-xl-3">
            <div class="card border-0 border-top border-danger border-3">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-danger">Tolak Open / Cancel</h6>
                    <h1 class="mb-3">{{ $reject }}</h1>
                    <p class="mb-0 text-muted text-sm">
                        Dicancel atau tidak close dengan penyebab tertentu 
                    </p>
                </div>
            </div>
        </div> --}}
    </div>

@endsection