@extends('layouts.app', ['title' => 'Department Configuration'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Department Configuration</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Department Configuration</a></li>
                        <li class="breadcrumb-item" aria-current="page">Department Configuration</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Department Configuration</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="d-flex justify-content-end">
                    <button type="button" id="admin-department-export" class="btn btn-sm btn-info rounded ms-2"><i class="ti ti-file"></i> Export</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="dept-config">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Admin</th>
                            <th>Akses Departemen</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection