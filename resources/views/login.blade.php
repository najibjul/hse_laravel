@extends('layouts.auth')
@section('content')
    <form action="{{ route('login-post') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="mb-0"><b>Login</b></h3>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">NIP</label>
                <input name="nip" type="text" class="form-control @error('nip') is-invalid @enderror"
                    placeholder="Masukan NIP disini..." required value="{{ old('nip') }}">
                @error('nip')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Kata sandi</label>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukan kata sandi disini..." required>
                @error('password')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="d-flex mt-1 justify-content-between">
                <div class="form-check">
                    <input class="form-check-input input-success" type="checkbox" id="customCheckc1" name="remember">
                    <label class="form-check-label text-muted" for="customCheckc1">Ingat saya</label>
                </div>
            </div>
            @if (session()->has('unauthenticated'))
                <div class="alert alert-danger mt-4" role="alert">
                    {{ session('unauthenticated') }}
                </div>
            @endif
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-success">Login</button>
            </div>
        </div>
    </form>
@endsection
