@extends('layouts.auth')
@section('content')
    <div class="p-3 text-center">
        <h2>Setup Google Authenticator</h2>

        <p>Scan QR code berikut dengan aplikasi <b>Google Authenticator</b>:</p>

        <div class="mb-3">
            {!! $qrcode !!}
        </div>

        <p>Atau masukkan kode rahasia manual:</p>
        <button type="button" onclick="copyToClipboard()" class="btn btn-sm btn-success rounded-pill">
            <code class="text-white">{{ $secret }} <i class="ti ti-clipboard"></i></code>
        </button>
        <input type="text" id="secret" value="{{ $secret }}" hidden>

        <hr>

        <form method="POST" action="{{ route('mfa.verify') }}">
            @csrf
            <div class="mb-3">
                <label>Masukkan kode OTP:</label>
                <input type="text" name="otp" class="form-control" placeholder="XXXXXX" required>
                @error('otp')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Verifikasi</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard() {
            var copyText = document.getElementById("secret");

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value);

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "Kode berhasil dicopy"
            });
        }
    </script>
@endpush
