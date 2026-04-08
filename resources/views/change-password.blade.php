@extends('layouts.auth')
@section('content')
    <div class="p-3">
        <h2>Ganti Password</h2>
        <p class="mb-4">Ubah password terlebih dahulu agar dapat mengakses aplikasi</p>

        <form action="{{ route('change-password.post') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label for="old-password">Password saat ini</label>
                <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" id="old-password" placeholder="XXXXXXXX" required oninput="validatePasswords()">
                @error('old_password')
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-2">
                <label for="password">Password baru</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="XXXXXXXX" required oninput="validatePasswords()">
                @error('password')
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror

            </div>
            <div class="mb-4">
                <label for="password-confirmation">Konfirmasi password baru</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password-confirmation" placeholder="XXXXXXXX" required oninput="validatePasswords()">
                @error('password_confirmation')
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div id="passwordValidation" class="my-2">
                    <small id="validationMessage" class="text-danger"></small>
                </div>
            <button type="submit" id="submitBtn" class="btn btn-warning w-100" disabled>Update</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function validatePasswords() {
            const oldPassword = document.getElementById('old-password').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password-confirmation').value;
            const submitBtn = document.getElementById('submitBtn');
            const validationMessage = document.getElementById('validationMessage');

            // Validasi
            const oldPasswordValid = oldPassword.length > 0;
            const requiredValid = password.length > 0;
            const minValid = password.length >= 8;
            const uppercaseValid = /[A-Z]/.test(password);
            const lowercaseValid = /[a-z]/.test(password);
            const numberValid = /[0-9]/.test(password);
            const symbolValid = /[@$!%*#?&.,]/.test(password);
            const confirmValid = password === passwordConfirmation && password.length > 0;

            // Buat array pesan error
            const errors = [];
            if (!oldPasswordValid) errors.push('Password lama wajib diisi');
            if (!requiredValid) errors.push('Password baru wajib diisi');
            if (!minValid) errors.push('Min 8 karakter');
            if (!uppercaseValid) errors.push('Huruf besar');
            if (!lowercaseValid) errors.push('Huruf kecil');
            if (!numberValid) errors.push('Angka');
            if (!symbolValid) errors.push('Simbol');
            if (!confirmValid && (password.length > 0 || passwordConfirmation.length > 0)) errors.push('Password tidak sama');

            // Tampilkan pesan
            if (errors.length > 0) {
                validationMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errors.join(', ');
                validationMessage.classList.remove('text-success');
                validationMessage.classList.add('text-danger');
            } else {
                validationMessage.innerHTML = '<i class="fas fa-check-circle"></i> Semua kriteria terpenuhi';
                validationMessage.classList.remove('text-danger');
                validationMessage.classList.add('text-success');
            }

            // Enable/Disable Submit Button
            const allValid = oldPasswordValid && requiredValid && minValid && uppercaseValid && lowercaseValid && numberValid && symbolValid && confirmValid;
            submitBtn.disabled = !allValid;
        }

        // Jalankan validasi saat halaman load
        document.addEventListener('DOMContentLoaded', validatePasswords);
    </script>
@endpush
