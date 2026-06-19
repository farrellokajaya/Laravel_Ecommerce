<x-guest-layout>
    <div class="auth-card-header">
        <h2>Confirm password</h2>
        <p>This is a secure area. Confirm your password to continue.</p>
    </div>

    <form class="auth-form" method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" id="password" type="password" name="password" autocomplete="current-password" required>
            @foreach($errors->get('password') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>
        <button class="button button-dark button-wide" type="submit">Confirm password</button>
    </form>
</x-guest-layout>
