<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'email', 'mobile_no'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
