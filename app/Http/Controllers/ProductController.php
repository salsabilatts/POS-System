<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('product.index', compact('products'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_product' => 'required|unique:product,id_product|string|max:20',
            'product_name' => 'required|unique:product',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'satuan' => 'required|string|max:50',
            ], [
            'id_product.unique' => 'ID produk sudah digunakan. Gunakan ID lain.',
            'product_name.unique' => 'Nama produk sudah ada. Gunakan nama lain.',
        ]);

        Product::create($request->all());

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'satuan' => 'required|string|max:50',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
