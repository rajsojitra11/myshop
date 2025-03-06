<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;

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
