<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // ambil keyword pencarian dari form
        $search = $request->input('search');

        // query product + pencarian + pagination
        $products = Product::when($search, function ($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%")
                             ->orWhere('product_code', 'like', "%{$search}%");
            })
            ->paginate(5);

        // biar query search tetap ikut di pagination link
        $products->appends(['search' => $search]);

        return view('products.index', compact('products', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|unique:master_products,product_code|max:20',
            'product_name' => 'required|string|max:200',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0'
        ]);

        DB::statement("CALL sp_create_product(?, ?, ?, ?)", [
            $request->product_code,
            $request->product_name,
            $request->price,
            $request->stock_quantity
        ]);

        return redirect()->route('products.index')->with('success', 'Product created!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:200',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'required|boolean'
        ]);

        DB::statement("CALL sp_update_product(?, ?, ?, ?, ?)", [
            $id,
            $request->product_name,
            $request->price,
            $request->stock_quantity,
            $request->is_active
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated!');
    }

    public function destroy($id)
    {
        DB::statement("CALL sp_soft_delete_product(?)", [$id]);
        return redirect()->route('products.index')->with('success', 'Product deleted (soft delete)!');
    }
}