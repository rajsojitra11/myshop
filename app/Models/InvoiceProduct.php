<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $fillable = [
        'invoice_id',
        'serial',
        'name',
        'description',
        'qty',
        'rate',
        'amount'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
