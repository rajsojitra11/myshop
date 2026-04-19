<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPassword extends Model
{
    protected $fillable = [
        'email',
        'mobile_no',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}
