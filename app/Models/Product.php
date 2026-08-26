<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'meta_keys',
        'meta_description',
        'description',
        'is_visible',
        'is_hot',
        'is_new',
        'price',
        'sale_price',
        'sale_start_date',
        'sale_end_date',
        'barcode',
        'amount_in_stock',
        'route',
        'position',
        'brand_id',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_hot' => 'boolean',
        'is_new' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
    ];

    public function attachments(): HasMany
    {
        return $this->hasMany(ProductAttachment::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'products_categories');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function isOnSale(): bool
    {
        if (is_null($this->sale_price)) {
            return false;
        }

        $now = now();

        return $now->between($this->sale_start_date, $this->sale_end_date);
    }

    public function getCurrentPriceAttribute(): string
    {
        return $this->isOnSale() ? $this->sale_price : $this->price;
    }

}
