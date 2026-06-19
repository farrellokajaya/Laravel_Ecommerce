<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Giftos') }}</title>
    <link rel="icon" href="/front_end/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="/front_end/css/user.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-visual">
            <a class="brand auth-brand" href="{{ route('home') }}">
                <span class="brand-mark">G</span>
                <span>Giftos</span>
            </a>
            <div class="auth-message">
                <span>Welcome to Giftos</span>
                <h1>Shopping made simple.</h1>
                <p>Discover selected products, manage your cart, and complete secure payments in one clean experience.</p>
            </div>
            <span class="auth-footnote">Secure checkout powered by Stripe.</span>
        </section>

        <section class="auth-panel">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </section>
    </main>
</body>
</html>
