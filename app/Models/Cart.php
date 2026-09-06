<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['user_id', 'guest_token'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Cart $cart) {
            $cart->id = (string) Str::uuid();
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
