<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show($order_id)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('order_id', $order_id)
            ->firstOrFail();
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled,refunded'
        ]);

        $order = Order::where('order_id', $order_id)->firstOrFail();
        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diupdate!');
    }

    public function updatePaymentStatus(Request $request, $order_id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $order = Order::where('order_id', $order_id)->firstOrFail();
        $order->payment_status = $request->payment_status;
        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status pembayaran berhasil diupdate!');
    }
}