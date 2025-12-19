@extends('layouts.app', ['title' => 'Edit Cost Center'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cost-centers.index') }}">Master Cost Center</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Edit Cost Center</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-9">


            <div class="card">
                <div class="card-header">
                    <h4>Edit Cost Center</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cost-centers.update', encrypt($costCenter->id)) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="mb-3">
                            <label for="cost-center">Nama cost center</label>
                            <input id="cost-center" type="text"
                                class="form-control @error('costCenter') is-invalid @enderror "
                                placeholder="Ketik disini ..." name="costCenter"
                                value="{{ substr($costCenter->cost_center_name, 0, 3) }}">
                            @error('costCenter')
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
                                        Edit data cost center?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-warning">Ya</button>
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
