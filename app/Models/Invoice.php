<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
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

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
