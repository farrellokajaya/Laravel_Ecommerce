<x-guest-layout>
    <div class="auth-card-header">
        <h2>Choose a new password</h2>
        <p>Create a strong password that you do not use elsewhere.</p>
    </div>

    <form class="auth-form" method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label for="email">Email address</label>
            <input class="form-control" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" autocomplete="username" autofocus required>
            @foreach($errors->get('email') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>
        <div class="form-group">
            <label for="password">New password</label>
            <input class="form-control" id="password" type="password" name="password" autocomplete="new-password" required>
            @foreach($errors->get('password') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm new password</label>
            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
        </div>
        <button class="button button-dark button-wide" type="submit">Reset password</button>
    </form>
</x-guest-layout>
