@extends('layouts.app', ['title' => 'Department Configuration'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tambah Admin - Departemen</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.dept-config.index') }}">Department Configuration</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Tambah Admin - Departemen</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Tambah Admin - Departemen</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="admin-dept-config">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
