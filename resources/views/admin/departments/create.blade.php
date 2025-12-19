@extends('layouts.app', ['title' => 'Tambah Departemen'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Master Departemen</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Tambah Departemen</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Departemen</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.departments.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="department">Nama Departemen</label>
                                <input type="text" id="department" class="form-control @error('department') is-invalid @enderror "
                                    placeholder="Masukan nama..." name="department" value="{{ old('department') }}">
                                @error('department')
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
                                        Simpan departemen?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-success">Ya</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
