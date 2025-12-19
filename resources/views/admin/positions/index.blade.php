@extends('layouts.app', ['title' => 'Master Posisi'])
@section('content')
    <div class="page-header" id="master-position-index">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Master Posisi</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-9">


            <div class="card">
                <div class="card-header">
                    <h4>Master Posisi</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.positions.create') }}" class="btn btn-sm btn-success rounded ms-2">
                                <i class="ti ti-plus"></i> Tambah
                            </a>
                            <button type="button" id="positionExport" class="btn btn-sm btn-info rounded ms-2"><i
                                    class="ti ti-file"></i> Export</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="positionTable">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama</th>
                                    <th class="text-center">Safety Comitee</th>
                                    <th class="text-center">Opsi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
