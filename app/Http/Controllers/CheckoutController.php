<?php


namespace App\Http\Controllers;


// app/Http/Controllers/CheckoutController.php


use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Wajib untuk data profil


class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []); // Ambil keranjang dari Session


        $items = [];
        $subtotal = 0;


        // 1. Ambil detail item dan hitung subtotal dari Session Cart
        foreach ($cart as $productId => $quantity) {
            // Gunakan product_id karena Product model menggunakan product_id sebagai primary key
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


        $shipping = 50000; // Contoh biaya pengiriman (sesuaikan)
        $total = $subtotal + $shipping;


        // 2. Ambil data user yang login dengan relasi customer dan address
        $user = Auth::user()->load(['customer', 'address']);


        // 3. Load view checkout
        return view('checkout.index', compact('items', 'subtotal', 'shipping', 'total', 'user'));


    // ...// app/Http/Controllers/CheckoutController.php


    // Menggantikan placeOrder sesuai routes Anda: checkout.store
    // app/Http/Controllers/CheckoutController.php
    }
    public function store(Request $request)
{
    // Ambil objek user yang sedang login
    $user = Auth::user();
   
    // 🛑 LANGKAH 1: Ambil data Customer melalui relasi
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


    // 1. Validasi Minimal: Hanya memvalidasi field yang masih ada di form (Payment Method & Terms)
    $request->validate([
        'payment_method' => 'nullable|string|max:255',
        'terms' => 'required', // Validasi checkbox terms
    ]);


    $cart = session()->get('cart', []);


    // Validasi cart tidak boleh kosong
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
    }


    // 🛑 LANGKAH 2: Pengecekan data Customer wajib sudah terisi
    // Sekarang menggunakan fallback, jadi cek minimal ada first_name atau user->name
    if (empty($firstName) && empty($user->name)) {
        return redirect()->back()->with('error', 'Mohon lengkapi nama di profil Anda sebelum melanjutkan.');
    }
   
    // Gunakan address_line_01 (tanpa 's') sesuai database
    if (empty($user->address?->address_line_01)) {
        return redirect()->back()->with('error', 'Mohon lengkapi alamat di profil Anda sebelum melanjutkan.');
    }


    DB::beginTransaction();


    try {
        // --- LOGIKA UTAMA: Hitung & Buat Order ---
        $subtotal = 0;
        $itemsData = [];


        // 1. Hitung Ulang Subtotal dan Siapkan Data Item
        // PERBAIKAN: Gunakan where('product_id', ...) bukan find() karena Product model menggunakan product_id sebagai primary key
        foreach ($cart as $productId => $quantity) {
            // PERBAIKAN: Gunakan where karena Product model menggunakan product_id sebagai primary key
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
                'product_id' => $product->product_id, // PERBAIKAN: Gunakan product_id bukan id
                'quantity' => $quantity,
                'price' => $unitCost,
            ];
        }


        if (empty($itemsData)) {
            throw new \Exception('Tidak ada produk valid di keranjang.');
        }


        $shipping = 50000;
        $total = $subtotal + $shipping;


        // 🛑 LANGKAH 3: Buat Order Baru - AMBIL DATA DARI TABEL CUSTOMER DENGAN FALLBACK
        $order = Order::create([
            'user_id' => $user->id,


            // =======================================================
            // PENGAMBILAN DATA DARI TABEL CUSTOMER DENGAN FALLBACK
            // =======================================================
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $customer?->email_id ?? $user->email,
            'phone' => $phoneNumber,
            'company_name' => $customer?->company_name ?? null,


            // Kolom Alamat - Gunakan address_line_01 (tanpa 's') sesuai database
            'shipping_address' => $customer?->shipping_address ?? $user->address?->address_line_01 ?? '',
            'city' => $customer?->city ?? $user->address?->town_city ?? '',
            'postcode' => $customer?->postcode ?? $user->address?->postcode_zip ?? '',
            'country' => $customer?->country ?? $user->address?->country ?? 'Indonesia',


            // Data Transaksi
            'payment_method' => $request->payment_method ?? 'paypal',
            'payment_status' => 'pending',
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $total,
        ]);


        // 3. Pindahkan Item Keranjang ke Order Items dan kurangi stock
        foreach ($itemsData as $item) {
            $itemTotal = $item['price'] * $item['quantity'];

            // PERBAIKAN INI:
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['price'],    // ← UBAH 'price' MENJADI 'unit_cost'
                'item_total' => $itemTotal,       // ← SESUAI (sudah benar)
            ]);

            Product::where('product_id', $item['product_id'])->decrement('stock', $item['quantity']);
        }


        // 4. Kosongkan Keranjang Belanja di Session
        session()->forget('cart');


        DB::commit();


        // 5. Redirect ke halaman konfirmasi
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


            // Debug: Uncomment baris ini untuk melihat data
            // dd([
            //     'order_id' => $order->id,
            //     'items_count' => $order->items->count(),
            //     'items' => $order->items->map(function($item) {
            //         return [
            //             'product_id' => $item->product_id,
            //             'product' => $item->product ? $item->product->product_name : 'NULL'
            //         ];
            //     })
            // ]);


            if (Auth::check() && $order->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this order.');
            }


            return view('checkout.confirmation', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    // ...// app/Http/Controllers/CheckoutController.php
}
