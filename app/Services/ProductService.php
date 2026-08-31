<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function list(array $filters): LengthAwarePaginator
    {
        $query = Product::with(['attachments', 'brand'])
            ->where('is_visible', true);

        if (!empty($filters['is_hot'])) {
            $query->where('is_hot', true);
        }

        if (!empty($filters['is_new'])) {
            $query->where('is_new', true);
        }

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('route', $filters['category']);
            });
        }

        if (!empty($filters['brand'])) {
            $query->whereHas('brand', function ($q) use ($filters) {
                $q->where('route', $filters['brand']);
            });
        }

        return $query->orderBy('position')->paginate(12);
    }

    public function findByRoute(string $route): Product
    {
        return Product::with(['attachments', 'categories', 'brand'])
            ->where('route', $route)
            ->firstOrFail();
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $query = Product::with(['attachments', 'brand'])->where('is_visible', true);

        if (!empty($filters['is_hot'])) {
            $query->where('is_hot', true);
        }

        if (!empty($filters['is_new'])) {
            $query->where('is_new', true);
        }

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category'])) {
            $query->whereHas('categories', fn ($q) => $q->where('route', $filters['category']));
        }

        if (!empty($filters['brand'])) {
            $query->whereHas('brand', fn ($q) => $q->where('route', $filters['brand']));
        }

        if (!empty($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }

        return $query->orderBy('position')->paginate(12);
    }
}
