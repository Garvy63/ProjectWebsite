<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
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
            'product_name' => 'required|max:100',
            'category' => 'nullable|max:50',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'unit_price' => 'required|numeric|min:0'
        ]);

        $imagePath = null;
        
        // Upload gambar jika ada
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('product_images'), $imageName);
            $imagePath = 'product_images/' . $imageName;
        }

        Product::create([
            'product_name' => $request->product_name,
            'category' => $request->category,
            'product_image' => $imagePath,
            'unit_price' => $request->unit_price
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
            'product_name' => 'required|max:100',
            'category' => 'nullable|max:50',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'unit_price' => 'required|numeric|min:0'
        ]);

        $product = Product::findOrFail($id);
        
        $imagePath = $product->product_image;
        
        // Upload gambar baru jika ada
        if ($request->hasFile('product_image')) {
            // Hapus gambar lama jika ada
            if ($product->product_image && file_exists(public_path($product->product_image))) {
                unlink(public_path($product->product_image));
            }
            
            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('product_images'), $imageName);
            $imagePath = 'product_images/' . $imageName;
        }

        $product->update([
            'product_name' => $request->product_name,
            'category' => $request->category,
            'product_image' => $imagePath,
            'unit_price' => $request->unit_price
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($product->product_image && file_exists(public_path($product->product_image))) {
            unlink(public_path($product->product_image));
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}