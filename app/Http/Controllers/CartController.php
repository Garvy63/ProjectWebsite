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
            // Gunakan where karena Product model menggunakan product_id sebagai primary key
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


        return view('cart.index', compact('items', 'subtotal'));
    }




    public function add(Request $request)
    {
        $productId = $request->product_id;
        $quantity = (int) $request->quantity;


        // Validasi ID dan ambil produk
        $product = Product::where('product_id', $productId)->first();
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan');
        }


        // Validasi stock
        $currentStock = $product->stock ?? 0;
        if ($currentStock <= 0) {
            return back()->with('error', 'Maaf, produk ini sedang habis stoknya.');
        }


        // Cek quantity yang diminta
        if ($quantity <= 0) {
            return back()->with('error', 'Jumlah produk harus lebih dari 0.');
        }


        // Cek apakah quantity melebihi stock
        $cart = session()->get('cart', []);
        $currentCartQuantity = $cart[$productId] ?? 0;
        $totalQuantity = $currentCartQuantity + $quantity;


        if ($totalQuantity > $currentStock) {
            return back()->with('error', 'Jumlah yang diminta melebihi stok yang tersedia. Stok tersedia: ' . $currentStock);
        }


        // HAPUS: Jangan kurangi stock di sini, stock hanya dikurangi saat checkout
        // $product->stock = $currentStock - $quantity;
        // $product->save();


        // Tambahkan ke cart
        $cart[$productId] = $totalQuantity;
        session()->put('cart', $cart);


        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang!');
    }


    public function checkout()
    {
        $cart = session()->get('cart', []);
       
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }


        // Hanya redirect ke halaman checkout, jangan langsung buat order
        return redirect()->route('checkout.index');
    }


    public function show($id)
    {
        // Gunakan product_id karena Product model menggunakan product_id sebagai primary key
        $product = Product::where('product_id', $id)->firstOrFail();
        return view('products.show', compact('product'));
    }


    function formatRupiah($value)
    {
        return 'Rp' . number_format($value, 0, ',', '.');
    }


    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $newQuantity = (int) $request->quantity;


        if (!isset($cart[$productId])) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ada di keranjang']);
        }


        $product = Product::where('product_id', $productId)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan']);
        }


        // Validasi stock tersedia (hanya cek, jangan kurangi)
        if ($newQuantity > ($product->stock ?? 0)) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi']);
        }


        // HAPUS: Jangan update stock di database, stock hanya dikurangi saat checkout
        // $oldQuantity = $cart[$productId];
        // $stockDifference = $newQuantity - $oldQuantity;
        // $product->stock = ($product->stock ?? 0) - $stockDifference;
        // $product->save();


        if ($newQuantity < 1) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $newQuantity;
        }


        session()->put('cart', $cart);
        return response()->json(['success' => true]);
    }


    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;


        if (isset($cart[$productId])) {
            // HAPUS: Jangan kembalikan stock, karena stock belum pernah dikurangi
            // $product = Product::where('product_id', $productId)->first();
            // if ($product) {
            //     $removedQuantity = $cart[$productId];
            //     $product->stock = ($product->stock ?? 0) + $removedQuantity;
            //     $product->save();
            // }


            unset($cart[$productId]); // hapus produk dari session
            session()->put('cart', $cart);
        }


        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang!');
    }
}


