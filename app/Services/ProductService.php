<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductService
{
    public function list(Request $request): LengthAwarePaginator
    {
        $query = Product::with('attachments')
            ->where('is_visible', true);

        if ($request->boolean('is_hot')) {
            $query->where('is_hot', true);
        }

        if ($request->boolean('is_new')) {
            $query->where('is_new', true);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('route', $request->input('category'));
            });
        }

        return $query->orderBy('position')->paginate(15);
    }

    public function findByRoute(string $route): Product
    {
        return Product::with(['attachments', 'categories'])
            ->where('route', $route)
            ->firstOrFail();
    }
}
