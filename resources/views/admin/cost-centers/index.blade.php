@extends('layouts.app', ['title' => 'Master Cost Center'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Master Cost Center</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Master Cost Center</a></li>
                        <li class="breadcrumb-item" aria-current="page">Master Cost Center</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Master Cost Center</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="d-flex justify-content-end">


                    <a href="{{ route('admin.cost-centers.create') }}" class="btn btn-success">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="costCenterTable">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#myTable').DataTable();
        })
    </script>
@endpush
