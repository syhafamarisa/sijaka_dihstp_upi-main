@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Ubah Password</h1>
        <p class="text-gray-600 mt-2">Perbarui password keamanan akun Anda</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <h3 class="font-bold mb-2">Terjadi kesalahan:</h3>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Ubah Password -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.profile.update-password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="current_password" class="block text-gray-700 font-semibold mb-2">Password Saat Ini</label>
                <div class="relative">
                    <input type="password" id="current_password" name="current_password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror"
                        placeholder="Masukkan password saat ini">
                    <button type="button" class="toggle-password absolute right-3 top-3 text-gray-500" data-target="current_password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="new_password" class="block text-gray-700 font-semibold mb-2">Password Baru</label>
                <div class="relative">
                    <input type="password" id="new_password" name="new_password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('new_password') border-red-500 @enderror"
                        placeholder="Masukkan password baru (minimal 8 karakter)">
                    <button type="button" class="toggle-password absolute right-3 top-3 text-gray-500" data-target="new_password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <p class="text-gray-600 text-sm mt-1">Password harus minimal 8 karakter</p>
                @error('new_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="new_password_confirmation" class="block text-gray-700 font-semibold mb-2">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Konfirmasi password baru Anda">
                    <button type="button" class="toggle-password absolute right-3 top-3 text-gray-500" data-target="new_password_confirmation">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Password Strength Indicator -->
            <div class="mb-6 p-4 bg-gray-100 rounded-lg">
                <p class="text-sm font-semibold text-gray-700 mb-2">Kekuatan Password:</p>
                <div id="password-strength-bar" class="w-full h-2 bg-gray-300 rounded-full overflow-hidden">
                    <div id="strength-indicator" class="h-full bg-red-500 w-0 transition-all"></div>
                </div>
                <p id="strength-text" class="text-xs text-gray-600 mt-2">Lemah</p>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Ubah Password
                </button>
                <a href="{{ route('admin.profile.show') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>

    <!-- Password Requirements -->
    <div class="bg-blue-50 rounded-lg p-6 mt-6">
        <h3 class="font-semibold text-blue-900 mb-3">Persyaratan Password yang Kuat:</h3>
        <ul class="text-blue-800 text-sm space-y-2">
            <li><i class="fas fa-check text-green-600 mr-2"></i>Minimal 8 karakter</li>
            <li><i class="fas fa-check text-green-600 mr-2"></i>Mengandung huruf besar dan kecil</li>
            <li><i class="fas fa-check text-green-600 mr-2"></i>Mengandung angka</li>
            <li><i class="fas fa-check text-green-600 mr-2"></i>Mengandung simbol khusus (!@#$%)</li>
        </ul>
    </div>
</div>

<script>
// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const target = this.getAttribute('data-target');
        const input = document.getElementById(target);
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});

// Password strength indicator
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;

    if (password.length >= 8) strength += 20;
    if (password.length >= 12) strength += 20;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 20;
    if (/\d/.test(password)) strength += 20;
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strength += 20;

    const bar = document.getElementById('strength-indicator');
    const text = document.getElementById('strength-text');

    bar.style.width = strength + '%';

    if (strength < 40) {
        bar.className = 'h-full bg-red-500 w-0 transition-all';
        text.textContent = 'Lemah';
    } else if (strength < 60) {
        bar.className = 'h-full bg-yellow-500 w-0 transition-all';
        text.textContent = 'Sedang';
    } else if (strength < 80) {
        bar.className = 'h-full bg-blue-500 w-0 transition-all';
        text.textContent = 'Kuat';
    } else {
        bar.className = 'h-full bg-green-500 w-0 transition-all';
        text.textContent = 'Sangat Kuat';
    }
    bar.style.width = strength + '%';
});
</script>
@endsection
