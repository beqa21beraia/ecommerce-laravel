<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'items' => $this->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'title' => $item->product->title,
                    'thumbnail' => optional($item->product->attachments->first())->file_url,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->current_price,
                    'line_total' => $item->product->current_price * $item->quantity,
                ];
            }),
            'total' => $this->items->sum(fn ($item) => $item->product->current_price * $item->quantity),
        ];
    }
}
