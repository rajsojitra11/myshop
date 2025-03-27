<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price'
    ];

    /**
     * Define the relationship between InvoiceProduct and Invoice.
     * Each InvoiceProduct belongs to an Invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Define the relationship between InvoiceProduct and Product.
     * Each InvoiceProduct is linked to a specific Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
