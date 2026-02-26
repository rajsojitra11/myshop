<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'company_name',
        'email',
        'address',
        'contact_no',
        'country',
        'bank_details',
        'product_categories'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the stock records for the supplier.
     */
    public function stocks()
    {
        return $this->hasMany(SupplierStock::class, 'supplier_id', 'id');
    }
}
