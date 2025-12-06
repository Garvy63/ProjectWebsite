<?php

// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;


class CartController extends Controller
{
    // --- Private Helper: Dapatkan ID Keranjang ---
    private function getCartIdentifier(Request $request)
    {
        // Untuk contoh ini, kita pakai Session ID
        // Di aplikasi nyata, Anda bisa pakai Auth::id() jika user login
        $sessionId = $request->session()->getId();
        return $sessionId;
    }

    // --- 1. Menampilkan Halaman Keranjang ---
    /**
     * Tampilkan halaman keranjang belanja
     */
    public function index(Request $request)
    {
        $identifier = $this->getCartIdentifier($request);

        // Ambil semua item keranjang beserta data produk
        $cartItems = CartItem::with('product')
                           ->where('session_id', $identifier)
                           ->get();

        // Hitung Subtotal
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        // Data yang dikirim ke view
        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    // --- 2. Menambahkan Produk ke Keranjang ---
    /**
     * Tambahkan produk baru ke keranjang atau update quantity jika sudah ada
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $identifier = $this->getCartIdentifier($request);
        $quantity = $request->input('quantity');
        
        // Cari item keranjang yang sudah ada
        $cartItem = CartItem::where('session_id', $identifier)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            // Update quantity jika item sudah ada
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Buat item keranjang baru
            CartItem::create([
                'session_id' => $identifier,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price, // Ambil harga produk saat ini
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // --- 3. Mengupdate Quantity Item Keranjang ---
    /**
     * Update quantity satu item di keranjang
     */
public function update(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1', // Validasi minimal 1
    ]);

    $identifier = $this->getCartIdentifier($request);
    $product_id = $request->input('product_id');
    $quantity = $request->input('quantity');

    $cartItem = CartItem::where('session_id', $identifier)
                        ->where('product_id', $product_id)
                        ->firstOrFail();
    
    // Logika Pembaruan
    $cartItem->quantity = $quantity;
    $cartItem->save();

    // Mengembalikan respons JSON karena ini biasanya dipanggil via AJAX
    return response()->json([
        'message' => 'Keranjang berhasil diperbarui!',
        'new_total' => number_format($cartItem->quantity * $cartItem->price, 2, ',', '.')
    ]);
}
public function applyCoupon(Request $request)
{
    $request->validate([
        'coupon_code' => 'required|string|max:20',
    ]);

    $code = strtoupper($request->input('coupon_code'));
    
    // Cari kupon di database
    $coupon = Coupon::where('code', $code)
                    ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                    ->first();

    if (!$coupon) {
        return back()->withErrors(['coupon_code' => 'Kode kupon tidak valid atau sudah kadaluarsa.']);
    }

    // SIMPAN DATA KUPON DI SESSION
    // Kita simpan objek kupon di session untuk dipakai di 'index'
    $request->session()->put('coupon', $coupon); 

    return back()->with('success', 'Kupon berhasil diterapkan!');
}

public function calculateShipping(Request $request)
{
    $request->validate([
        'country' => 'required',
        'state' => 'required',
        'postcode' => 'nullable|string',
    ]);

    $country = $request->input('country');
    $state = $request->input('state');
    
    // --- Logika Penentuan Biaya Kirim ---
    $shippingFee = 0;

    if ($country == 'Indonesia') {
        if ($state == 'Jawa Tengah') {
            $shippingFee = 20000;
        } elseif ($state == 'Jakarta') {
            $shippingFee = 35000;
        } else {
            $shippingFee = 50000;
        }
    } else {
        $shippingFee = 150000; // Biaya kirim internasional
    }
    // --- Akhir Logika ---

    // Simpan biaya kirim dan detailnya ke Session
    $request->session()->put('shipping_details', [
        'fee' => $shippingFee,
        'country' => $country,
        'state' => $state,
    ]);

    return back()->with('shipping_success', 'Biaya pengiriman berhasil dihitung!');
}

    // * Hapus satu item dari keranjang
    public function destroy(Request $request, $productId)
    {
        $identifier = $this->getCartIdentifier($request);

        CartItem::where('session_id', $identifier)
                ->where('product_id', $productId)
                ->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
    
    // Metode lain seperti applyCoupon, calculateShipping, dll. akan ditambahkan di sini.
}