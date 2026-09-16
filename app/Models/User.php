<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'phone',
        'phone_verified_at',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'role' => UserRole::class
    ];

    protected $hidden = ['password'];

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function favoriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }
}
