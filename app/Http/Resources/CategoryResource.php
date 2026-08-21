<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'title' => $this->title,
            'meta_keys' => $this->meta_keys,
            'meta_description' => $this->meta_description,
            'parent_id' => $this->parent_id,
            'route' => $this->route,
            'position' => $this->position,
        ];
    }
}
