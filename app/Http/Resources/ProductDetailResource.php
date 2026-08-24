<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'route' => $this->route,
            'description' => $this->description,
            'meta_keys' => $this->meta_keys,
            'meta_description' => $this->meta_description,
            'price' => $this->price,
            'current_price' => $this->current_price,
            'is_on_sale' => $this->isOnSale(),
            'amount_in_stock' => $this->amount_in_stock,
            'barcode' => $this->barcode,
            'attachments' => $this->attachments->pluck('file_url'),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'related_products' => ProductResource::collection($this->whenLoaded('relatedProducts')),
        ];
    }
}
