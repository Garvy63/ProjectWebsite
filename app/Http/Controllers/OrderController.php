<?php


namespace App\Http\Controllers;


use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }


    public function show($order_id)
    {
        // Ambil order dengan relasi items dan product
        $order = Order::with(['items.product'])
            ->where('order_id', $order_id)
            ->firstOrFail();
       
        // Pastikan order ini milik user yang login (security)
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }
        return view('orders.show', compact('order'));
    }
}


