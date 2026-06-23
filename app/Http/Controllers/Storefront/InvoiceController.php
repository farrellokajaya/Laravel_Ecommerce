<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(string $invoiceNumber)
    {
        $orders = Order::where(
            'invoice_number',
            $invoiceNumber
        )
            ->where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->with(['product', 'user'])
            ->orderBy('id')
            ->get();

        abort_if($orders->isEmpty(), 404);

        $firstOrder = $orders->first();

        $total = $orders->sum(function ($order) {
            return (float) $order->total_price;
        });

        $pdf = Pdf::loadView('invoices.user', compact(
            'orders',
            'firstOrder',
            'invoiceNumber',
            'total'
        ));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download(
            $invoiceNumber . '.pdf'
        );
    }
}
