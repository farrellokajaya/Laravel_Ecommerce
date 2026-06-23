<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', ''));
        $paymentStatus = trim((string) $request->query('payment_status', ''));

        $orders = Order::with(['user', 'product'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($orderQuery) use ($search) {
                    $orderQuery
                        ->where('receiver_name', 'like', '%' . $search . '%')
                        ->orWhere('receiver_address', 'like', '%' . $search . '%')
                        ->orWhere('receiver_phone', 'like', '%' . $search . '%')
                        ->orWhere('stripe_payment_id', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery
                                ->where('product_title', 'like', '%' . $search . '%')
                                ->orWhere('product_category', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($paymentStatus !== '', fn ($query) => $query->where('payment_status', $paymentStatus))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.vieworders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in progress,delivered,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()
            ->back()
            ->with('status_message', 'Order status updated successfully!');
    }

    public function downloadPdf($id)
    {
        $data = Order::with(['user', 'product'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.invoice', compact('data'));

        return $pdf->download('giftos-invoice-' . str_pad($data->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
