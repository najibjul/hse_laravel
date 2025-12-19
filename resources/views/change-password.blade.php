@extends('layouts.auth')
@section('content')
    <div class="p-3">
        <h2>Ganti Password</h2>
        <p class="mb-4">Ubah password terlebih dahulu agar dapat mengakses aplikasi</p>

        <form action="{{ route('change-password.post') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label for="old-password">Password saat ini</label>
                <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" id="old-password" placeholder="XXXXXXXX" required>
                @error('old_password')
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-2">
                <label for="password">Password baru</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="XXXXXXXX" required>
                @error('password')
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password-confirmation">Konfirmasi password baru</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password-confirmation" placeholder="XXXXXXXX" required>
                @error('password_confirmation')
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-warning w-100">Update</button>
        </form>
    </div>
@endsection
