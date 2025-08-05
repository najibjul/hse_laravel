@extends('layouts.app', ['title' => 'Edit Position'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Edit Position</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.positions.index') }}">Master Position</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Edit Position</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Edit Position</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.positions.update', encrypt($position->id)) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="mb-3">

                    <label>Nama position</label>
                    <input type="text" class="form-control @error('position') is-invalid @enderror "
                    placeholder="Ketik disini..." name="position"
                    value="{{ $position->position_name }}">
                    @error('position')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
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
                                Edit data Position?
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
