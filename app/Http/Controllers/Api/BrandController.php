<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\BrandDetailResource;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index()
    {
        $brands = $this->brandService->getAllBrands();
        return BrandResource::collection($brands);
    }

    public function show($route)
    {
        $brand = $this->brandService->findByRoute($route);
        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
        return new BrandDetailResource($brand);
    }
}
