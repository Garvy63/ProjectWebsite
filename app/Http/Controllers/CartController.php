<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []); // contoh: [product_id => quantity]
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if ($product) {
                $unitCost = $product->unit_price;
                $itemTotal = $unitCost * $quantity;

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'item_total' => $itemTotal,
                ];

                $subtotal += $itemTotal;
            }
        }

        return view('cart.index', compact('items', 'subtotal'));
    }


    public function add(Request $request)
    {
        $productId = $request->product_id;
        $quantity = (int) $request->quantity;

        // Validasi ID dulu kalau perlu
        $product = Product::find($productId);
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan');
        }

        $cart = session()->get('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $products = Product::whereIn('product_id', array_keys($cart))->get();

        $total = 0;

        // Buat order baru
        $order = Order::create([
            'customer_id' => Auth::id(), // pastikan ini sesuai dengan relasi user->customer
            'billing_address_id' => 1,
            'shipping_address_id' => 1,
            'order_date' => now(),
            'subtotal' => 0,
            'shipping_cost' => 0,
            'total_amount' => 0,
            'status' => 'pending',
            'payment_method' => 'Cash',
            'terms_accepted' => 1,
        ]);

        foreach ($products as $product) {
            $qty = $cart[$product->product_id];
            $itemTotal = $product->unit_price * $qty;

            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $product->product_id,
                'quantity' => $qty,
                'unit_cost' => $product->unit_price,
                'item_total' => $itemTotal,
            ]);

            $total += $itemTotal;
        }

        $order->update([
            'subtotal' => $total,
            'total_amount' => $total,
        ]);

        session()->forget('cart');

        return redirect()->route('orders.show', ['id' => $order->order_id]);
    }
    function formatRupiah($value)
    {
        return 'Rp' . number_format($value, 0, ',', '.');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $quantity = (int) $request->quantity;

        if ($quantity < 1) {
            unset($cart[$productId]); // auto delete permanen
        } else {
            $cart[$productId] = $quantity;
        }

        session()->put('cart', $cart);
        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]); // hapus produk dari session
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang!');
    }
}
