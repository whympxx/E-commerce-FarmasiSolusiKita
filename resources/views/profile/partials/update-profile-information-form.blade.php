<section>
    <header class="mb-4">
        <h2 class="h5 fw-bold text-primary">
            {{ __('Profile Information') }}
        </h2>
        <p class="text-muted small">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="needs-validation animate__animated animate__fadeIn" novalidate>
        @csrf
        @method('patch')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-2 p-2 animate__animated animate__fadeIn">
                    <span>{{ __('Your email address is unverified.') }}</span>
                    <button form="send-verification" class="btn btn-link p-0 align-baseline">{{ __('Click here to re-send the verification email.') }}</button>
                </div>
                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2 animate__animated animate__fadeIn">{{ __('A new verification link has been sent to your email address.') }}</div>
                @endif
            @endif
        </div>
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary animate__animated animate__pulse animate__infinite">{{ __('Save') }}</button>
            @if (session('status') === 'profile-updated')
                <span class="text-success animate__animated animate__fadeIn">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
