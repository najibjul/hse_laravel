@extends('layouts.auth')
@section('content')
    <form action="{{ route('login-post') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="mb-0"><b>Login</b></h3>
            </div>
            <div class="form-group mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input id="nip" name="nip" type="text" class="form-control @error('nip') is-invalid @enderror"
                    placeholder="Masukan NIP disini..." required value="{{ old('nip') }}">
                @error('nip')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Kata sandi</label>
                <input name="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukan kata sandi disini..." required >
                @error('password')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
                {{-- <div id="passwordValidation" class="mt-2">
                    <small id="validationMessage" class="text-danger"></small>
                </div> --}}
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
                {{-- <button type="submit" id="submitBtn" class="btn btn-success" disabled>Login</button> --}}
                <button type="submit" id="submitBtn" class="btn btn-success" >Login</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- <script>
        function validatePassword() {
            const nip = document.getElementById('nip').value.trim();
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submitBtn');
            const validationMessage = document.getElementById('validationMessage');

            const nipValid = nip.length > 0;
            const requiredValid = password.length > 0;
            const minValid = password.length >= 8;
            const uppercaseValid = /[A-Z]/.test(password);
            const lowercaseValid = /[a-z]/.test(password);
            const numberValid = /[0-9]/.test(password);
            const symbolValid = /[@$!%*#?&.,]/.test(password);

            const errors = [];
            if (!nipValid) errors.push('NIP wajib diisi');
            if (!requiredValid) errors.push('Password wajib diisi');
            if (!minValid) errors.push('Min 8 karakter');
            if (!uppercaseValid) errors.push('Huruf besar');
            if (!lowercaseValid) errors.push('Huruf kecil');
            if (!numberValid) errors.push('Angka');
            if (!symbolValid) errors.push('Simbol');

            if (errors.length > 0) {
                validationMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errors.join(', ');
                validationMessage.classList.remove('text-success');
                validationMessage.classList.add('text-danger');
            } else {
                validationMessage.innerHTML = '<i class="fas fa-check-circle"></i> Semua kriteria terpenuhi';
                validationMessage.classList.remove('text-danger');
                validationMessage.classList.add('text-success');
            }

            const allValid = nipValid && requiredValid && minValid && uppercaseValid && lowercaseValid && numberValid && symbolValid;
            submitBtn.disabled = !allValid;
        }

        document.getElementById('nip').addEventListener('input', validatePassword);

        document.addEventListener('DOMContentLoaded', validatePassword);
    </script> --}}
@endpush
