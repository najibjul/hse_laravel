@extends('layouts.app', ['title' => 'Edit Departemen'])
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Edit Departemen</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Master Departemen</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Edit Departemen</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Edit Departemen</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.departments.update', encrypt($department->id)) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Nama Departemen</label>
                        <input type="text" class="form-control @error('department_name') is-invalid @enderror "
                            placeholder="Masukan nama departemen..." name="department_name" required
                            value="{{ $department->department_name }}">
                        @error('department_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label>Department Head</label>
                        <select id="depthead" class="form-control @error('department_head') is-invalid @enderror" name="department_head">
                            <option @if($department->depthead?->user) value="{{ $department->depthead->user_id }}" selected @endif> @if($department->depthead?->user) {{ $department->depthead?->user?->name }} ({{ $department->depthead?->user?->nip }}) @endif</option>
                        </select>
                        @error('department_head')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
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
                                Edit data departemen?
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

    @push('styles')
        <link rel="stylesheet" href="{{ asset('select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('select2-bootstrap-5-theme.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('select2.min.js') }}"></script>

        <script>
            $(function() {
                let url = "{{ route('admin.departments.edit.search-dh', ':id') }}"
                url = url.replace(':id', {{ $department->id }})

                $('#depthead').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Search item code...',
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name + ' (' + item.nip + ')',
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            });
        </script>
    @endpush
@endsection
