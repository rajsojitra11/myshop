<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all products for this user
        $products = Product::where('uid', $user->id)->get();

        // Calculate available quantity
        foreach ($products as $product) {
            // Sum qty from invoice_products matching product name
            // Recommended: Use product_id in future for more reliability
            $soldQty = InvoiceProduct::where('name', $product->name)->sum('qty');
            $product->available_quantity = $product->stock_quantity - $soldQty;
        }

        return view('admin.product', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'      => 'required|unique:products,code',
            'name'      => 'required|string|max:255',
            'price'     => 'required|numeric|min:0',
            'quantity'  => 'required|integer|min:1',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('index')->with('error', 'You must be logged in to add a product.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'code'           => $request->code,
            'name'           => $request->name,
            'price'          => $request->price,
            'stock_quantity' => $request->quantity,
            'image'          => $imagePath,
            'uid'            => $user->id,
        ]);

        Log::info("Product created successfully: ", $product->toArray());

        return redirect()->route('product.index')->with('pasuccess', 'Product added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($id);

        if ($product->uid !== Auth::id()) {
            return redirect()->route('product.index')->with('error', 'Unauthorized action.');
        }

        $product->update([
            'code' => $request->code,
            'name' => $request->name,
            'price' => $request->price,
            'stock_quantity' => $request->quantity,
        ]);

        return redirect()->route('product.index')->with('psuccess', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->uid !== Auth::id()) {
            return redirect()->route('product.index')->with('error', 'Unauthorized action.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('product.index')->with('pdsuccess', 'Product deleted successfully!');
    }
}
