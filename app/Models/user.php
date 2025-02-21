<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Table name

    protected $primaryKey = 'uid'; // Custom primary key field

    public $incrementing = true; // Auto-incrementing primary key

    protected $keyType = 'int'; // Primary key data type

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed', // Auto-hashes passwords (Laravel 10+)
    ];
}
