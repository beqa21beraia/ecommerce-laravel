<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return ProductResource::collection(
            $this->productService->list($request)
        );
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'is_hot' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255|alpha_dash',
            'brand' => 'nullable|string|max:255|alpha_dash',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',
        ]);

        return ProductResource::collection(
            $this->productService->search($validated)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $route)
    {
        return new ProductDetailResource(
            $this->productService->findByRoute($route)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
