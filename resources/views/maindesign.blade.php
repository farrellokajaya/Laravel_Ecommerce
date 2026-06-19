<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="A modern shopping experience for carefully selected products.">
    <title>@yield('title', 'Giftos')</title>

    <link rel="icon" href="/front_end/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="/front_end/css/user.css">
    @stack('styles')
</head>
<body>
    <header class="site-header" id="siteHeader">
        <div class="container header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Giftos home">
                <span class="brand-mark">G</span>
                <span>Giftos</span>
            </a>

            <button class="mobile-menu-button" type="button" data-menu-toggle aria-label="Open navigation" aria-expanded="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>

            <div class="header-content" data-mobile-menu>
                <nav class="main-nav" aria-label="Primary navigation">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('viewallproducts') }}" class="{{ request()->routeIs('viewallproducts', 'product_details') ? 'active' : '' }}">Products</a>

                    <details class="nav-dropdown">
                        <summary>Categories
                            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="m5 7.5 5 5 5-5"/></svg>
                        </summary>
                        <div class="dropdown-menu">
                            <a href="{{ route('viewallproducts') }}">All Categories</a>
                            @foreach(($navCategories ?? collect()) as $category)
                                <a href="{{ route('viewallproducts', ['category' => $category]) }}">{{ $category }}</a>
                            @endforeach
                        </div>
                    </details>
                </nav>

                <form class="header-search" action="{{ route('viewallproducts') }}" method="GET" role="search">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search products" aria-label="Search products">
                </form>

                <div class="header-actions">
                    <a class="icon-link cart-link" href="{{ route('cartproduct') }}" aria-label="Shopping cart">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.1 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 7H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                        @if(($navCartCount ?? 0) > 0)
                            <span class="cart-count">{{ $navCartCount }}</span>
                        @endif
                    </a>

                    @auth
                        <details class="account-dropdown">
                            <summary class="account-trigger">
                                <span class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                <span class="account-name">{{ Auth::user()->name }}</span>
                                <svg viewBox="0 0 20 20" aria-hidden="true"><path d="m5 7.5 5 5 5-5"/></svg>
                            </summary>
                            <div class="dropdown-menu account-menu">
                                <div class="account-summary">
                                    <strong>{{ Auth::user()->name }}</strong>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                                <a href="{{ route('dashboard') }}">Dashboard</a>
                                <a href="{{ route('myorders') }}">My Orders</a>
                                <a href="{{ route('profile.edit') }}">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit">Log Out</button>
                                </form>
                            </div>
                        </details>
                    @else
                        <a class="text-link" href="{{ route('login') }}">Log in</a>
                        <a class="button button-dark button-small" href="{{ route('register') }}">Sign up</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a class="brand footer-brand" href="{{ route('home') }}">
                    <span class="brand-mark">G</span>
                    <span>Giftos</span>
                </a>
                <p>Thoughtfully selected products, simple checkout, and a shopping experience designed around you.</p>
            </div>
            <div>
                <h3>Shop</h3>
                <a href="{{ route('viewallproducts') }}">All Products</a>
                @foreach(($navCategories ?? collect())->take(3) as $category)
                    <a href="{{ route('viewallproducts', ['category' => $category]) }}">{{ $category }}</a>
                @endforeach
            </div>
            <div>
                <h3>Account</h3>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('myorders') }}">My Orders</a>
                    <a href="{{ route('profile.edit') }}">Profile</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    <a href="{{ route('register') }}">Create account</a>
                @endauth
            </div>
        </div>
        <div class="container footer-bottom">
            <span>&copy; {{ date('Y') }} Giftos. All rights reserved.</span>
            <span>Secure payments powered by Stripe.</span>
        </div>
    </footer>

    <script src="/front_end/js/user.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('payment_success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Payment successful',
                    text: @json(session('payment_success')),
                    confirmButtonText: 'Continue shopping',
                    confirmButtonColor: '#111111'
                });
            });
        </script>
    @endif

    @if(session('cart_message') || session('confirm_order') || session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.showStoreToast('success', @json(session('cart_message') ?? session('confirm_order') ?? session('success')));
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.showStoreToast('error', @json(session('error')));
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Please check your information',
                    html: @json('<div class="validation-popup">'.implode('<br>', $errors->all()).'</div>'),
                    confirmButtonColor: '#111111'
                });
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
