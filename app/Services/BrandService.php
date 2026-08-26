<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandService
{
    /**
     * Get a listing of brands.
     *
     * @return Collection
     */
    public function getAllBrands(): Collection
    {
        return Brand::orderBy('name')->get();
    }

    public function getBrandById(int $id): ?Brand
    {
        return Brand::with('products')->find($id);
    }

    /**
     * Find a brand by its route.
     *
     * @param string $route
     * @return Brand
     */
    public function findByRoute(string $route): Brand
    {
        return Brand::with(['products.attachments'])
            ->where('route', $route)
            ->firstOrFail();
    }
}
