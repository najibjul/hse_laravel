@extends('layouts.app')
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tambah User</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Master User</a></li>
                        <li class="breadcrumb-item" aria-current="page">Tambah User</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Tambah User</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror "
                            placeholder="Masukan nama..." name="name" required>
                            @error('name')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label>NIP</label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror " placeholder="Masukan NIP..."
                            name="nip" required>
                            @error('nip')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror "
                            placeholder="Masukan email..." name="email" required>
                            @error('email')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label>Departemen</label>
                        <select name="department" class="form-control @error('department') is-invalid @enderror " required>
                            <option value="">-Pilih departemen-</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        @error('department')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror " required>
                            <option value="">-Pilih role-</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label>Position</label>
                        <select name="position" class="form-control @error('position') is-invalid @enderror " required>
                            <option value="">-Pilih position-</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                            @endforeach
                        </select>
                        @error('position')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#create"><i
                        class="ti ti-device-floppy"></i> Simpan</button>

                <div class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Simpan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Simpan data user?
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