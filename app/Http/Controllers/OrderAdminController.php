<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
   // READ - Index (List all orders)
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }

    // CREATE - Show create form
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        $users = User::with(['customer', 'address'])->get();
        
        return view('admin.orders.create', compact('products', 'users'));
    }

    // CREATE - Store new order
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'required|string',
            'postcode' => 'required|string',
            'payment_method' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,product_id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Hitung subtotal
            $subtotal = 0;
            $productItems = [];
            
            foreach ($request->products as $productData) {
                // FIX: Gunakan where karena primary key adalah product_id
                $product = Product::where('product_id', $productData['product_id'])->first();
                
                if (!$product) {
                    throw new \Exception('Produk dengan ID ' . $productData['product_id'] . ' tidak ditemukan');
                }
                
                // Validasi stok
                if ($product->stock < $productData['quantity']) {
                    throw new \Exception('Stok produk ' . $product->product_name . ' tidak mencukupi. Stok tersedia: ' . $product->stock);
                }
                
                $itemTotal = $product->unit_price * $productData['quantity'];
                $subtotal += $itemTotal;
                
                $productItems[] = [
                    'product' => $product,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $product->unit_price,
                    'item_total' => $itemTotal
                ];
            }

            $shipping = 50000; // Default shipping
            $total = $subtotal + $shipping;

            // Create order
            $order = Order::create([
                'user_id' => $request->user_id ?? null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'country' => $request->country ?? 'Indonesia',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
            ]);

            // Create order items
            foreach ($productItems as $item) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $item['product']->product_id,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_price'],
                    'item_total' => $item['item_total'],
                ]);

                // Kurangi stock
                $item['product']->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating order: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    // READ - Show single order
    public function show($order_id)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('order_id', $order_id)
            ->firstOrFail();
        
        return view('admin.orders.show', compact('order'));
    }

    // UPDATE - Show edit form
    public function edit($order_id)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('order_id', $order_id)
            ->firstOrFail();
        
        $users = User::with(['customer', 'address'])->get();
        
        return view('admin.orders.edit', compact('order', 'users'));
    }

    // UPDATE - Update order
    public function update(Request $request, $order_id)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'required|string',
            'postcode' => 'required|string',
            'payment_method' => 'required|string',
            'status' => 'required|in:pending,completed,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order = Order::where('order_id', $order_id)->firstOrFail();
        
        $order->update($request->only([
            'first_name', 'last_name', 'email', 'phone',
            'shipping_address', 'city', 'postcode', 'country',
            'payment_method', 'status', 'payment_status'
        ]));

        return redirect()->route('admin.orders.show', $order->order_id)
            ->with('success', 'Pesanan berhasil diperbarui!');
    }

    // Update Status Only
    public function updateStatus(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
            'payment_status' => 'nullable|in:pending,paid,failed',
        ]);

        $order = Order::where('order_id', $order_id)->firstOrFail();
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status ?? $order->payment_status,
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // Update Payment Status Only
    public function updatePaymentStatus(Request $request, $order_id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order = Order::where('order_id', $order_id)->firstOrFail();
        
        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }
}