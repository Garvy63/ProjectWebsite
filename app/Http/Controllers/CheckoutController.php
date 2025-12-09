<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        // Ambil detail item dan hitung subtotal dari Session Cart
        foreach ($cart as $productId => $quantity) {
            $product = Product::where('product_id', $productId)->first();

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

        // Validasi: Jika cart kosong, redirect ke cart
        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong. Silakan tambahkan produk.');
        }

        $shipping = 50000;
        $total = $subtotal + $shipping;

        // Ambil data user yang login dengan relasi customer dan address
        $user = Auth::user()->load(['customer', 'address']);

        return view('checkout.index', compact('items', 'subtotal', 'shipping', 'total', 'user'));
    }

    public function store(Request $request)
    {
        // Ambil objek user yang sedang login
        $user = Auth::user();
       
        // Ambil data Customer melalui relasi
        $customer = $user->customer;
       
        // Helper untuk mendapatkan first_name dan last_name dengan fallback
        $firstName = $customer?->first_name;
        if (empty($firstName) && !empty($user->name)) {
            $nameParts = explode(' ', trim($user->name), 2);
            $firstName = $nameParts[0] ?? $user->name;
        }
       
        $lastName = $customer?->last_name;
        if (empty($lastName) && !empty($user->name)) {
            $nameParts = explode(' ', trim($user->name), 2);
            $lastName = $nameParts[1] ?? '';
        }
       
        $phoneNumber = $customer?->phone_number ?? '';

        // Validasi
        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:255',
            'terms' => 'required',
        ], [
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        $cart = session()->get('cart', []);

        // Validasi cart tidak boleh kosong
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Pengecekan data Customer wajib sudah terisi
        if (empty($firstName) && empty($user->name)) {
            return redirect()->back()->with('error', 'Mohon lengkapi nama di profil Anda sebelum melanjutkan.');
        }
       
        if (empty($user->address?->address_line_01)) {
            return redirect()->back()->with('error', 'Mohon lengkapi alamat di profil Anda sebelum melanjutkan.');
        }

        DB::beginTransaction();

        try {
            // Hitung Ulang Subtotal dan Siapkan Data Item
            $subtotal = 0;
            $itemsData = [];

            foreach ($cart as $productId => $quantity) {
                $product = Product::where('product_id', $productId)->first();

                if (!$product) {
                    continue;
                }

                if (($product->stock ?? 0) < $quantity) {
                    throw new \Exception('Stok produk ' . $product->product_name . ' tidak mencukupi.');
                }

                $unitCost = $product->unit_price;
                $subtotal += ($unitCost * $quantity);

                $itemsData[] = [
                    'product_id' => $product->product_id,
                    'quantity' => $quantity,
                    'price' => $unitCost,
                ];
            }

            if (empty($itemsData)) {
                throw new \Exception('Tidak ada produk valid di keranjang.');
            }

            $shipping = 50000;
            $total = $subtotal + $shipping;

            // Buat Order Baru - HAPUS company_name dari sini
            $order = Order::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $customer?->email_id ?? $user->email,
                'phone' => $phoneNumber,
                // 'company_name' => $customer?->company_name ?? null, // DIHAPUS atau di-comment
                'shipping_address' => $customer?->shipping_address ?? $user->address?->address_line_01 ?? '',
                'city' => $customer?->city ?? $user->address?->town_city ?? '',
                'postcode' => $customer?->postcode ?? $user->address?->postcode_zip ?? '',
                'country' => $customer?->country ?? $user->address?->country ?? 'Indonesia',
                'payment_method' => $request->payment_method ?? 'paypal',
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
            ]);

            // Pindahkan Item Keranjang ke Order Items dan kurangi stock
            foreach ($itemsData as $item) {
                $itemTotal = $item['price'] * $item['quantity'];

                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'item_total' => $itemTotal,
                ]);

                Product::where('product_id', $item['product_id'])->decrement('stock', $item['quantity']);
            }

            // Kosongkan Keranjang Belanja di Session
            session()->forget('cart');

            DB::commit();

            // Redirect ke halaman konfirmasi
            return redirect()->route('checkout.confirmation', ['orderId' => $order->id])
                ->with('success', 'Pesanan berhasil dibuat! Terima kasih atas pembelian Anda.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    public function confirmation($orderId)
    {
        try {
            $order = Order::with(['items.product'])->findOrFail($orderId);

            if (Auth::check() && $order->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this order.');
            }

            return view('checkout.confirmation', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}