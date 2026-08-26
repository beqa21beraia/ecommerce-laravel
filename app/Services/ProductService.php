<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductService
{
    public function list(Request $request): LengthAwarePaginator
    {
        $validated = $request->validate([
            'is_hot' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255|alpha_dash',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Product::with('attachments')
            ->where('is_visible', true);

        if (!empty($validated['is_hot'])) {
            $query->where('is_hot', true);
        }

        if (!empty($validated['is_new'])) {
            $query->where('is_new', true);
        }

        if (!empty($validated['search'])) {
            $query->where('title', 'like', '%' . $validated['search'] . '%');
        }

        if (!empty($validated['category'])) {
            $query->whereHas('categories', function ($q) use ($validated) {
                $q->where('route', $validated['category']);
            });
        }

        return $query->orderBy('position')->paginate(12);
    }

    public function findByRoute(string $route): Product
    {
        return Product::with(['attachments', 'categories'])
            ->where('route', $route)
            ->firstOrFail();
    }
}
