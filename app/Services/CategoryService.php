<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function getTree(): Collection
    {
        return Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('position')
            ->get();
    }
}
