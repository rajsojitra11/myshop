<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'price', 'stock_quantity', 'image', 'user_id'];


    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class, 'product_id');
    }

    // Scope to get products with low available stock (less than 10)
    // Available stock = stock_quantity - sold_quantity
    public function scopeLowStock($query)
    {
        return $query->whereRaw('(stock_quantity - COALESCE((
            SELECT SUM(qty) FROM invoice_products WHERE invoice_products.name = products.name
        ), 0)) < 10');
    }

    // Get available quantity for a product
    public function getAvailableQuantityAttribute()
    {
        $soldQty = InvoiceProduct::where('name', $this->name)->sum('qty');
        return $this->stock_quantity - $soldQty;
    }
}
