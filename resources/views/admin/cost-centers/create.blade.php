@extends('layouts.app', ['title' => 'Create Cost Center'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tambah Cost Center</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cost-centers.index') }}">Master Cost Center</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Tambah Department</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Tambah Cost Center</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cost-centers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 mb-3">
                        <label>Nama Cost center</label>
                        <input type="text" class="form-control @error('costCenter') is-invalid @enderror "
                            placeholder="Ketik disini ..." name="costCenter">
                        @error('costCenter')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#create"><i
                        class="ti ti-device-floppy"></i> Simpan</button>

                <div class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Simpan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Simpan cost center?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-success">Ya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
