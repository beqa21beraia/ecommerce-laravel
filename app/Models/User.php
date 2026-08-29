<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'phone',
        'phone_verified_at',
        'first_name',
        'last_name',
        'email',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];
}
