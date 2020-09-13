<div class="card card-login">
    <form class="form" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="card-header card-header-primary text-center">
            <h4 class="card-title">{{ __('Login') }}</h4>
        </div>
        <div class="card-body pt-5">
            <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="material-icons">mail</i>
                </span>
                </div>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email...">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>
            <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="material-icons">lock_outline</i>
                </span>
                </div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"
                placeholder="Password...">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Remember Me') }}
                    <span class="form-check-sign">
                    <span class="check"></span>
                    </span>
                </label>
            </div>
            </div>

        </div>
        <div class="footer d-flex align-items-center justify-content-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">{{ __('Login') }}</button>
        </div>
        @if (Route::has('password.request'))
            <div class="col-md-12 text-center">
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        @endif
    </form>
</div>