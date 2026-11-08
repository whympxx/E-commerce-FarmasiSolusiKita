<x-guest-layout>
    <x-slot name="title">Daftar</x-slot>

    <div class="mb-4">
        <h2 class="h3 mb-1">Buat akun baru</h2>
        <p class="text-muted mb-0">Daftar gratis untuk belanja lebih mudah dan melacak pesanan.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama -->
        <div class="mb-3">
            <x-input-label for="name" value="Nama Lengkap" />
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <x-text-input id="name" type="text" name="name" :value="old('name')"
                              class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                              placeholder="Nama lengkap Anda" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div class="mb-3">
            <x-input-label for="email" value="Email" />
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <x-text-input id="email" type="email" name="email" :value="old('email')"
                              class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                              placeholder="nama@email.com" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" value="Password" />
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <x-text-input id="password" type="password" name="password"
                              class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                              placeholder="Minimal 8 karakter" required autocomplete="new-password" />
                <button class="btn btn-outline-secondary border" type="button" data-toggle-password="password" title="Lihat password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                              placeholder="Ulangi password" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit" class="btn btn-primary btn-auth w-100">
            <i class="fas fa-user-plus me-2"></i>Daftar
        </button>
    </form>

    <p class="text-center text-muted mt-4 mb-0">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Masuk di sini</a>
    </p>
</x-guest-layout>
