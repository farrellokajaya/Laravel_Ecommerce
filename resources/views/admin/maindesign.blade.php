<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Giftos ecommerce administration panel.">
    <title>@yield('title', 'Admin') · Giftos</title>

    <link rel="icon" href="/front_end/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="/admin/css/admin-modern.css">
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar" aria-label="Admin navigation">
            <div class="sidebar-brand-row">
                <a class="admin-brand" href="{{ route('admin.dashboard') }}" aria-label="Giftos admin dashboard">
                    <span class="admin-brand-mark">G</span>
                    <span class="admin-brand-copy">
                        <strong>Giftos</strong>
                        <small>Admin console</small>
                    </span>
                </a>

                <button class="sidebar-close" type="button" data-sidebar-close aria-label="Close navigation">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <nav class="sidebar-nav">
                <span class="sidebar-label">Overview</span>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h6V4H4v9Zm10 7h6v-9h-6v9ZM4 20h6v-3H4v3Zm10-13h6V4h-6v3Z"/></svg>
                    <span>Dashboard</span>
                </a>

                <span class="sidebar-label">Catalog</span>

                <a href="{{ route('admin.viewproduct') }}" class="sidebar-link {{ request()->routeIs('admin.viewproduct', 'admin.searchproduct', 'admin.updateproduct') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z"/><path d="M12 11v10"/></svg>
                    <span>Products</span>
                </a>

                <a href="{{ route('admin.addproduct') }}" class="sidebar-link {{ request()->routeIs('admin.addproduct') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    <span>Add product</span>
                </a>

                <a href="{{ route('admin.viewcategory') }}" class="sidebar-link {{ request()->routeIs('admin.viewcategory', 'admin.categoryupdate') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z"/></svg>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.addcategory') }}" class="sidebar-link {{ request()->routeIs('admin.addcategory') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    <span>Add category</span>
                </a>

                <span class="sidebar-label">Sales</span>

                <a href="{{ route('admin.vieworder') }}" class="sidebar-link {{ request()->routeIs('admin.vieworder') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12l2 5H4l2-5Z"/><path d="M5 8v13h14V8M9 12h6"/></svg>
                    <span>Orders</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="{{ route('home') }}" class="sidebar-store-link">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 5h5v5M13 11l6-6"/><path d="M19 13v6H5V5h6"/></svg>
                    <span>View storefront</span>
                </a>
            </div>
        </aside>

        <div class="sidebar-overlay" data-sidebar-overlay></div>

        <div class="admin-workspace">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-label="Open navigation" aria-controls="adminSidebar" aria-expanded="false">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                    </button>

                    <div class="topbar-heading">
                        <span class="topbar-eyebrow">Admin panel</span>
                        <h1>@yield('page_title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="topbar-actions">
                    @yield('page_actions')

                    <details class="admin-account">
                        <summary class="admin-account-trigger">
                            <span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                            <span class="admin-account-copy">
                                <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
                                <small>Administrator</small>
                            </span>
                            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="m5 7.5 5 5 5-5"/></svg>
                        </summary>

                        <div class="admin-account-menu">
                            <div class="account-menu-summary">
                                <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
                                <span>{{ auth()->user()->email ?? '' }}</span>
                            </div>
                            <a href="{{ route('profile.edit') }}">Profile settings</a>
                            <a href="{{ route('home') }}">Open storefront</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Log out</button>
                            </form>
                        </div>
                    </details>
                </div>
            </header>

            <main class="admin-main">
                @if(session('category_message') || session('category_updated_message') || session('product_message') || session('status_message') || session('success'))
                    <div class="admin-alert admin-alert-success" role="status">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                        <span>{{ session('category_message') ?? session('category_updated_message') ?? session('product_message') ?? session('status_message') ?? session('success') }}</span>
                        <button type="button" data-alert-close aria-label="Dismiss notification">×</button>
                    </div>
                @endif

                @if(session('deletecategory_message') || session('deleteproduct_message') || session('error'))
                    @php
                        $adminMessage = session('deletecategory_message') ?? session('deleteproduct_message') ?? session('error');
                        $isBlockedMessage = str_contains(strtolower($adminMessage), 'cannot') || str_contains(strtolower($adminMessage), 'failed');
                    @endphp
                    <div class="admin-alert {{ $isBlockedMessage ? 'admin-alert-danger' : 'admin-alert-success' }}" role="status">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            @if($isBlockedMessage)
                                <path d="M12 8v5M12 17h.01"/><circle cx="12" cy="12" r="9"/>
                            @else
                                <path d="m5 12 4 4L19 6"/>
                            @endif
                        </svg>
                        <span>{{ $adminMessage }}</span>
                        <button type="button" data-alert-close aria-label="Dismiss notification">×</button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="admin-footer">
                <span>&copy; {{ date('Y') }} Giftos Admin.</span>
                <span>Manage your store with confidence.</span>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/admin/js/admin-modern.js"></script>
    @stack('scripts')
</body>
</html>
