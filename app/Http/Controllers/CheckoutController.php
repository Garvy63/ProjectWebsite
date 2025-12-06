<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Aksi 1: Menampilkan halaman formulir checkout
public function index(Request $request) // <--- TAMBAHKAN INI
{
    // 1. Ambil Session ID, gunakan Request yang di-inject (lebih aman)
    $sessionId = $request->session()->getId(); // <--- UBAH session()->getId() ke $request->session()->getId()
    
    // 2. Ambil item keranjang berdasarkan session_id
    $cartItems = CartItem::with('product')->where('session_id', $sessionId)->get();
    
        // Pastikan keranjang tidak kosong
        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Keranjang belanja Anda kosong. Silakan tambahkan produk.');
        }

        // 3. Hitung total
        $subtotal = $cartItems->sum(function($item) {
            // Asumsi model CartItem memiliki relasi product dan product memiliki kolom price
            return $item->quantity * $item->product->price; 
        });
        $shipping = 50.00; 
        $total = $subtotal + $shipping;

        // 4. Load view checkout dan kirim data
        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    // Aksi 2: Memproses pesanan dari form POST
    public function placeOrder(Request $request)
    {
        // 1. Validasi formulir (Wajib!)
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'add1' => 'required|string|max:255', // Address line 01
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:10', // Postcode
            // 'country_select' => 'required|string', // Jika Anda menggunakan field select
        ]);

        // 2. Ambil Session ID dan Keranjang
        $sessionId = session()->getId();
        $cartItems = CartItem::with('product')->where('session_id', $sessionId)->get();

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Keranjang belanja kosong.');
        }

        // 3. Mulai Transaksi Database untuk memastikan data konsisten
        DB::beginTransaction();
        
        try {
            // Hitung Ulang Total
            $subtotal = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });
            $shipping = 50.00;
            $total = $subtotal + $shipping;

            // 4. Buat Order Baru di tabel 'orders'
            $order = Order::create([
                'session_id' => $sessionId, 
                // Jika ada login, gunakan 'user_id' => auth()->id()
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'payment_method' => 'check_payments', // Ambil dari input jika ada
                'shipping_address' => $request->add1 . ' ' . $request->add2,
                'city' => $request->city,
                'postcode' => $request->zip,
                'country' => 'ID', // Ganti jika menggunakan field country
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
            ]);

            // 5. Pindahkan Item Keranjang ke Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price, 
                ]);
            }

            // 6. Kosongkan Keranjang Belanja
            CartItem::where('session_id', $sessionId)->delete();

            DB::commit();

            // 7. Redirect ke halaman konfirmasi
            return redirect()->route('checkout.confirmation', ['orderId' => $order->id]);

        } catch (\Exception $e) {
            DB::rollback();
            // Tampilkan error (hanya untuk development)
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Aksi 3: Menampilkan halaman konfirmasi
    public function confirmation($orderId)
    {
        // 1. Ambil data Order beserta item dan produknya
        $order = Order::with('items.product')->findOrFail($orderId);

        // 2. Verifikasi keamanan (pastikan order milik guest ini)
        if ($order->session_id !== session()->getId()) { 
             abort(403, 'Akses pesanan ini ditolak.'); 
        }

        // 3. Load view konfirmasi
        return view('confirmation', compact('order'));
    }
}