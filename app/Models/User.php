<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
}
