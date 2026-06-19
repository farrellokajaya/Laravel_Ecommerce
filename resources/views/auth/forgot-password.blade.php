<x-guest-layout>
    <div class="auth-card-header">
        <h2>Reset password</h2>
        <p>Enter your email and we will send you a password reset link.</p>
    </div>

    @if(session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email address</label>
            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" autofocus required>
            @foreach($errors->get('email') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>
        <button class="button button-dark button-wide" type="submit">Send reset link</button>
    </form>

    <p class="auth-switch"><a class="auth-link" href="{{ route('login') }}">Back to login</a></p>
</x-guest-layout>
