<x-guest-layout>
    <x-slot name="title">Masuk</x-slot>

    <div class="mb-4">
        <h2 class="h3 mb-1">Selamat datang kembali 👋</h2>
        <p class="text-muted mb-0">Masuk untuk melanjutkan belanja dan memantau pesanan Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <x-input-label for="email" value="Email" />
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <x-text-input id="email" type="email" name="email" :value="old('email')"
                              class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                              placeholder="nama@email.com" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <x-input-label for="password" value="Password" />
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none mb-2" href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <x-text-input id="password" type="password" name="password"
                              class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                              placeholder="Masukkan password" required autocomplete="current-password" />
                <button class="btn btn-outline-secondary border" type="button" data-toggle-password="password" title="Lihat password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label text-muted">Ingat saya</label>
        </div>

        <button type="submit" class="btn btn-primary btn-auth w-100">
            <i class="fas fa-right-to-bracket me-2"></i>Masuk
        </button>
    </form>

    @if (Route::has('register'))
        <p class="text-center text-muted mt-4 mb-0">
            Belum punya akun?
            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Daftar sekarang</a>
        </p>
    @endif
</x-guest-layout>
