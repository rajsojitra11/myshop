<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'bill_no',
        'invoice_number',
        'order_id',
        'account_number',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'payment_method'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
