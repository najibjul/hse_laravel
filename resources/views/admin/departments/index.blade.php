@extends('layouts.app', ['title' => 'Master Department'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Master Department</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Master Department</a></li>
                        <li class="breadcrumb-item" aria-current="page">Master Department</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Master Department</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="d-flex justify-content-end">


                    <a href="{{ route('admin.departments.create') }}" class="btn btn-success">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="myTable">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Department Head</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $department->department_name }}</td>
                                <td>{{ $department->depthead?->name }} @if ($department->depthead?->nip)
                                        ({{ $department->depthead?->nip }})
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.departments.edit', encrypt($department->id)) }}">
                                            <i class="ti ti-edit text-warning"></i>
                                        </a>
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#delete{{ $department->id }}">
                                            <i class="ti ti-trash text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="delete{{ $department->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Hapus {{ $department->name }}?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Kembali</button>
                                            <form action="{{ route('admin.departments.destroy', $department->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
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
