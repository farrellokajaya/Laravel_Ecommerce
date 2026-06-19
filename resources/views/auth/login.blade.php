<x-guest-layout>
    <div class="auth-card-header">
        <h2>Welcome back</h2>
        <p>Log in to continue shopping and manage your orders.</p>
    </div>

    @if(session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email address</label>
            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" autofocus required>
            @foreach($errors->get('email') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" id="password" type="password" name="password" autocomplete="current-password" required>
            @foreach($errors->get('password') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>

        <div class="auth-row">
            <label class="checkbox-label" for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Remember me</span>
            </label>
            @if(Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button class="button button-dark button-wide" type="submit">Log in</button>
    </form>

    <p class="auth-switch">New to Giftos? <a class="auth-link" href="{{ route('register') }}">Create an account</a></p>
</x-guest-layout>
