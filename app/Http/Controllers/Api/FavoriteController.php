<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        return ProductResource::collection(
            $request->user()->favoriteProducts()->with('attachments')->get()
        );
    }

    public function toggle(Request $request, Product $product)
    {
        $request->user()->favoriteProducts()->toggle($product->id);

        $isFavorited = $request->user()->favoriteProducts()->where('product_id', $product->id)->exists();

        return response()->json(['is_favorited' => $isFavorited]);
    }
}
