@extends('layouts.app', ['title' => 'Create Plant'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tambah Plant</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.plants.index') }}">Master Plant</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Tambah Plant</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Tambah Plant</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.plants.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 mb-3">
                        <label>Nama Plant</label>
                        <input type="text" class="form-control @error('plant') is-invalid @enderror "
                            placeholder="Masukan nama..." name="plant"
                            value="{{ old('plant') }}">
                        @error('plant')
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
                                Simpan plant?
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
