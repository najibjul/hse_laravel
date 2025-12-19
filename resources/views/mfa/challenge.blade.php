@extends('layouts.auth')

@section('content')
<div class="p-3">
<h2>Verifikasi OTP</h2>
<p>Masukkan kode 6 digit dari aplikasi Google Authenticator:</p>

<form method="POST" action="{{ route('mfa.verify') }}">
    @csrf
    <div class="mb-3">
        <input type="text" name="otp" class="form-control" placeholder="XXXXXX" required>
        @error('otp') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-success">Verifikasi</button>
</form>
</div>
@endsection
