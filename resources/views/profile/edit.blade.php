@extends('maindesign')

@section('title', 'Profile Settings — Giftos')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Account settings</span>
                <h1>Your profile</h1>
                <p>Keep your personal information and account security up to date.</p>
            </div>
        </div>

        <div class="profile-grid">
            <section class="profile-card">
                <h2>Profile information</h2>
                <p>Update your name and the email address associated with your account.</p>

                <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-stack">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" autocomplete="name" required autofocus>
                            @foreach($errors->get('name') as $message)<p class="form-error">{{ $message }}</p>@endforeach
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input class="form-control" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" autocomplete="username" required>
                            @foreach($errors->get('email') as $message)<p class="form-error">{{ $message }}</p>@endforeach
                        </div>
                    </div>

                    @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="verify-note">
                            Your email address is not verified.
                            <button class="link-button" type="submit" form="send-verification">Resend verification email</button>
                        </div>
                    @endif

                    <div class="form-actions">
                        <button class="button button-dark" type="submit">Save changes</button>
                        @if(session('status') === 'profile-updated')
                            <span class="inline-success">Profile saved.</span>
                        @endif
                        @if(session('status') === 'verification-link-sent')
                            <span class="inline-success">Verification link sent.</span>
                        @endif
                    </div>
                </form>
            </section>

            <section class="profile-card">
                <h2>Update password</h2>
                <p>Use a strong, unique password to keep your account protected.</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-stack">
                        <div class="form-group">
                            <label for="current_password">Current password</label>
                            <input class="form-control" id="current_password" name="current_password" type="password" autocomplete="current-password">
                            @foreach($errors->updatePassword->get('current_password') as $message)<p class="form-error">{{ $message }}</p>@endforeach
                        </div>
                        <div class="form-group">
                            <label for="new_password">New password</label>
                            <input class="form-control" id="new_password" name="password" type="password" autocomplete="new-password">
                            @foreach($errors->updatePassword->get('password') as $message)<p class="form-error">{{ $message }}</p>@endforeach
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm new password</label>
                            <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                            @foreach($errors->updatePassword->get('password_confirmation') as $message)<p class="form-error">{{ $message }}</p>@endforeach
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="button button-dark" type="submit">Update password</button>
                        @if(session('status') === 'password-updated')
                            <span class="inline-success">Password updated.</span>
                        @endif
                    </div>
                </form>
            </section>

            <section class="profile-card full danger-zone">
                <h2>Delete account</h2>
                <p>Permanently remove your account and all related data. This action cannot be undone.</p>
                <button class="button button-danger" type="button" onclick="document.getElementById('deleteAccountDialog').showModal()">Delete account</button>
            </section>
        </div>
    </div>
</section>

<dialog class="store-dialog" id="deleteAccountDialog">
    <form class="dialog-content" method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('DELETE')
        <h2>Delete your account?</h2>
        <p>Enter your password to confirm permanent deletion of your account.</p>
        <div class="form-group">
            <label for="delete_password">Password</label>
            <input class="form-control" id="delete_password" name="password" type="password" placeholder="Your password">
            @foreach($errors->userDeletion->get('password') as $message)<p class="form-error">{{ $message }}</p>@endforeach
        </div>
        <div class="dialog-actions">
            <button class="button button-light" type="button" onclick="document.getElementById('deleteAccountDialog').close()">Cancel</button>
            <button class="button button-danger" type="submit">Delete permanently</button>
        </div>
    </form>
</dialog>

@if($errors->userDeletion->isNotEmpty())
    @push('scripts')
        <script>document.addEventListener('DOMContentLoaded', () => document.getElementById('deleteAccountDialog').showModal());</script>
    @endpush
@endif
@endsection
