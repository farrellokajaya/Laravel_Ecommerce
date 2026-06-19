@extends('maindesign')

@section('title', 'Dashboard — Giftos')

@section('content')
<section class="account-hero">
    <div class="container account-welcome">
        <div>
            <span class="eyebrow">Your account</span>
            <h1>Hello, {{ Auth::user()->name }}.</h1>
            <p>Manage your shopping activity and account details from one place.</p>
        </div>
        <a class="button button-dark" href="{{ route('viewallproducts') }}">Continue shopping</a>
    </div>
</section>

<section class="page-section" style="padding-top: 30px;">
    <div class="container">
        <div class="dashboard-grid">
            <a class="dashboard-card" href="{{ route('myorders') }}">
                <span class="dashboard-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 3h12v18H6z"/><path d="M9 7h6M9 11h6M9 15h4"/></svg>
                </span>
                <h2>My orders</h2>
                <p>View payment information, delivery details, and current order status.</p>
                <span>View order history →</span>
            </a>

            <a class="dashboard-card" href="{{ route('cartproduct') }}">
                <span class="dashboard-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 4h2l2.1 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 7H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                </span>
                <h2>Shopping cart</h2>
                <p>You currently have {{ $navCartCount ?? 0 }} item{{ ($navCartCount ?? 0) === 1 ? '' : 's' }} waiting in your cart.</p>
                <span>Review cart →</span>
            </a>

            <a class="dashboard-card" href="{{ route('profile.edit') }}">
                <span class="dashboard-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                </span>
                <h2>Profile settings</h2>
                <p>Update your name, email address, password, and account security.</p>
                <span>Manage profile →</span>
            </a>
        </div>
    </div>
</section>
@endsection
