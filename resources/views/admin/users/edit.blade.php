@extends('layouts.app')
@section('content')
    <div class="page-header" id="master-user-create">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Edit User</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Master User</a></li>
                        <li class="breadcrumb-item" aria-current="page">Edit User</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Edit User</h4>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#mfa"><i class="ti ti-refresh"></i> Reset MFA</button>
            </div>

            <div class="modal fade" id="mfa" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Reset MFA</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Reset MFA untuk user <b>{{ $user->name }} ({{ $user->nip }})</b>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                <form method="POST" action="{{ route('mfa.reset', $user->id) }}">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-info">Ya</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-6 mb-4">
                        <label>Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror "
                            placeholder="Masukan nama..." name="name" required value="{{ $user->name }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-4">
                        <label>NIP</label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror "
                            placeholder="Masukan NIP..." name="nip" required value="{{ $user->nip }}">
                        @error('nip')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-4">
                        <label>Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror "
                            placeholder="Masukan email..." name="email" value="{{ $user->email }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-4">
                        <label>Role</label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror " required>
                            <option value="">-Pilih role-</option>
                            @foreach ($roles as $role)
                                <option {{ $role->id == $user->role_id ? 'selected' : '' }} value="{{ $role->id }}">
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
                            <select name="department" id="department-select" class="form-control" style="width: 100%;"
                                data-department="{{ old('department', $user->department_id) ?? '' }}"></select>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label>Position</label>
                        <div class="@error('position') select2-invalid @enderror">
                            <select name="position" id="position-select" class="form-control" style="width: 100%;"
                                data-position="{{ old('position', $user->position_id) ?? '' }}"></select>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label>Cost center</label>
                        <div class="@error('costCenter') select2-invalid @enderror">
                            <select name="costCenter" id="cost-center-select" class="form-control" style="width: 100%;"
                                data-cost-center="{{ old('costCenter', $user->cost_center_id) ?? '' }}"></select>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label>Plant</label>
                        <div class="@error('plant') select2-invalid @enderror">
                            <select name="plant" id="plant-select" class="form-control" style="width: 100%;"
                                data-plant="{{ old('plant', $user->plant_id) ?? '' }}"></select>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label>Leader</label>
                        <div class="@error('leader') select2-invalid @enderror">
                            <select name="leader" id="leader-select" class="form-control" style="width: 100%;"
                                data-leader="{{ old('leader', $user->leader_id) ?? '' }}"></select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#update"><i
                        class="ti ti-device-floppy"></i> Update</button>

                <div class="modal fade" id="update" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Edit data user?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-warning">Ya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
