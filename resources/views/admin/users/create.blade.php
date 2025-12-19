@extends('layouts.app', ['title' => 'Tambah User'])
@section('content')
    <div class="page-header" id="master-user-create">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    
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
                    <div class="col-6 mb-4">
                        <label>Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror "
                            placeholder="Masukan nama..." name="name" required value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-4">
                        <label>NIP</label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror "
                            placeholder="Masukan NIP..." name="nip" required value="{{ old('nip') }}">
                        @error('nip')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-4">
                        <label>Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror "
                            placeholder="Masukan email..." name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-4">
                        <label>Role</label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror " required>
                            <option value="">-Pilih role-</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->role_name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-4">
                        <label>Departemen </label>
                        <div class="@error('department') select2-invalid @enderror">
                            <select name="department" id="department-select" class="form-control" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label>Position</label>
                        <div class="@error('position') select2-invalid @enderror">
                            <select name="position" id="position-select" class="form-control" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label>Cost center</label>
                        <div class="@error('costCenter') select2-invalid @enderror">
                            <select name="costCenter" id="cost-center-select" class="form-control" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label>Plant</label>
                        <div class="@error('plant') select2-invalid @enderror">
                            <select name="plant" id="plant-select" class="form-control" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label>Leader</label>
                        <div class="@error('leader') select2-invalid @enderror">
                            <select name="leader" id="leader-select" class="form-control" style="width: 100%;"></select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add"><i
                        class="ti ti-plus"></i> Tambah</button>

                <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Tambah user baru?
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