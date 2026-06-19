<x-guest-layout>
    <div class="auth-card-header">
        <h2>Verify your email</h2>
        <p>Open the verification link we sent to your email before continuing.</p>
    </div>

    @if(session('status') === 'verification-link-sent')
        <div class="auth-status">A new verification link has been sent to your email address.</div>
    @endif

    <p class="auth-copy">Did not receive the email? Request another verification link below.</p>

    <div class="auth-actions-split">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="button button-dark" type="submit">Resend email</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="button button-light" type="submit">Log out</button>
        </form>
    </div>
</x-guest-layout>
