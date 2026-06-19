<x-guest-layout>
    <div class="auth-card-header">
        <h2>Create account</h2>
        <p>Join Giftos to save your cart and track every order.</p>
    </div>

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Full name</label>
            <input class="form-control" id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" autofocus required>
            @foreach($errors->get('name') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
            @foreach($errors->get('email') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" id="password" type="password" name="password" autocomplete="new-password" required>
            @foreach($errors->get('password') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm password</label>
            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
        </div>

        <button class="button button-dark button-wide" type="submit">Create account</button>
    </form>

    <p class="auth-switch">Already registered? <a class="auth-link" href="{{ route('login') }}">Log in</a></p>
</x-guest-layout>
