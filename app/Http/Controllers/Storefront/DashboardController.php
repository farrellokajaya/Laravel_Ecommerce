<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    }
}
