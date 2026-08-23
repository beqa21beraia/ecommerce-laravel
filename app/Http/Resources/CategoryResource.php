<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'route' => $this->route,
            'position' => $this->position,
            'children' => CategoryResource::collection($this->whenLoaded('children'))
        ];
    }
}
