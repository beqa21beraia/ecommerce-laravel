<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'route' => $this->route,
            'price' => $this->price,
            'current_price' => $this->current_price,
            'is_on_sale' => $this->isOnSale(),
            'is_hot' => $this->is_hot,
            'is_new' => $this->is_new,
            'thumbnail' => optional($this->attachments->first())->file_url
        ];
    }
}
