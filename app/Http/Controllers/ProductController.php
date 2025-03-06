<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'code'      => 'required|unique:products,code',
            'name'      => 'required|string|max:255',
            'price'     => 'required|numeric',
            'quantity'  => 'required|integer',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Insert into database
        Product::create([
            'code'      => $request->code,
            'name'      => $request->name,
            'price'     => $request->price,
            'stock_quantity' => $request->quantity,
            'image'     => $imagePath
        ]);

        return response()->json(['success' => 'Product added successfully!']);
    }
}
