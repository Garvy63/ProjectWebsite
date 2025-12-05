<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // =========================
    // FRONTEND
    // =========================
    public function frontendIndex()
    {
        $products = Product::orderBy('product_id', 'desc')->paginate(12);
        return view('index.index', compact('products'));
    }

    // =========================
    // ADMIN CRUD
    // =========================
    public function index()
    {
        $products = Product::orderBy('product_id', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name'  => 'required|string|max:100',
            'category'      => 'nullable|string|max:50',
            'product_image' => 'nullable|image|max:2048',
            'unit_price'    => 'required|numeric|min:0',
            'description'   => 'nullable|string|max:500',
            'stock'         => 'required|integer|min:0',
        ]);

        $imagePath = null;

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('product_images'), $imageName);
            $imagePath = 'product_images/' . $imageName;
        }

        Product::create([
            'product_name'  => $request->product_name,
            'category'      => $request->category,
            'unit_price'    => $request->unit_price,
            'description'   => $request->description,
            'stock'         => $request->stock,
            'product_image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name'  => 'required|string|max:100',
            'category'      => 'nullable|string|max:50',
            'product_image' => 'nullable|image|max:2048',
            'unit_price'    => 'required|numeric|min:0',
            'description'   => 'nullable|string|max:500',
            'stock'         => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);

        $imagePath = $product->product_image;

        if ($request->hasFile('product_image')) {
            if ($product->product_image && file_exists(public_path($product->product_image))) {
                unlink(public_path($product->product_image));
            }

            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('product_images'), $imageName);
            $imagePath = 'product_images/' . $imageName;
        }

        $product->update([
            'product_name'  => $request->product_name,
            'category'      => $request->category,
            'unit_price'    => $request->unit_price,
            'description'   => $request->description,
            'stock'         => $request->stock,
            'product_image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->product_image && file_exists(public_path($product->product_image))) {
            unlink(public_path($product->product_image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function showBanner()
    {
        // Ambil produk terbaru
        $latestProduct = Product::orderBy('created_at', 'desc')->first();

        return view('index.banner', compact('latestProduct'));
    }
}
