<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierStock extends Model
{
    use HasFactory;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'user_id', 'product', 'quantity', 'description', 'amount', 'date', 'supplier_id'
    ];

    /**
     * Cast date attribute to a Carbon instance.
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * A supplier stock record belongs to a supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    /**
     * A supplier stock record belongs to a user (who added it).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}