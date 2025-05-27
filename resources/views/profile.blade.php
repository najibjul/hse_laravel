@extends('layouts.app', ['title' => auth()->user()->name])
@section('content')
    <div class=" mt-4">
        <div class="card rounded">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div>
                        <h4 class="mb-0">{{ Auth::user()->name }}</h4>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                </div>

                <hr>
                <p><strong>Nama : </strong> {{ Auth::user()->name }}</p>
                <p><strong>NIP : </strong> {{ Auth::user()->nip }}</p>
                <p><strong>Email : </strong> {{ Auth::user()->email }}</p>
                <p><strong>Departemen : </strong> {{ Auth::user()->department->department_name }}</p>
                <p><strong>Role : </strong> {{ Auth::user()->role->role_name }}</p>

                <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#edit">
                    Ganti Password
                </button>

                <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Ganti password</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('profile.update') }}">
                                <div class="modal-body">
                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Password Saat Ini</label>
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror " required>
                                        @error('current_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror " required>
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password
                                            Baru</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror " required>
                                        @error('password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            @if(session()->has('success'))
                Swal.fire({
                    title: "Berhasil",
                    text: `{{ session('success') }}`,
                    icon: "success",
                    confirmButtonColor: "#198754",
                });
            @endif

            @if(session()->has('error'))
                Swal.fire({
                    title: "Error",
                    text: `{{ session('error') }}`,
                    icon: "error",
                    confirmButtonColor: "#dc3545"
                });
            @endif
            });
    </script>
@endpush