<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->with(['product', 'user'])
            ->latest()
            ->get();

        return view('viewmyorders', compact('orders'));
    }
}