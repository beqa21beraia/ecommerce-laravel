<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttachment extends Model
{
    protected $table = 'products_attachments';

    protected $fillable = ['product_id', 'file_url', 'is_visible', 'position'];

    protected $casts = ['is_visible' => 'boolean'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
