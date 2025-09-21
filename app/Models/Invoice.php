<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'to_name',
        'to_address',
        'to_email',
        'mobile_no',
        'bill_no',
        'invoice_number',
        'order_id',
        'account_number',
        'payment_method',
        'subtotal',
        'tax',
        'shipping',
        'total'
    ];

    // Relation to products
    public function products()
    {
        return $this->hasMany(InvoiceProduct::class, 'invoice_id', 'id');
    }
}
