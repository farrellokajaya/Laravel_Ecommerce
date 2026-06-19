<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('maindesign', function ($view) {
            $navCategories = Product::where('product_quantity', '>', 0)
                ->whereNotNull('product_category')
                ->where('product_category', '!=', '')
                ->select('product_category')
                ->distinct()
                ->orderBy('product_category')
                ->take(8)
                ->pluck('product_category');

            $navCartCount = Auth::check()
                ? (int) ProductCart::where('user_id', Auth::id())->sum('quantity')
                : 0;

            $view->with(compact('navCategories', 'navCartCount'));
        });
    }
}
