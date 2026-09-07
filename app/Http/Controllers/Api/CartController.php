<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function show(Request $request)
    {
        $cart = $this->resolveCart($request);

        return $this->respondWithCart($cart);
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->resolveCart($request);
        $product = Product::findOrFail($validated['product_id']);

        try {
            $this->cartService->addItem($cart, $product, $validated['quantity']);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return $this->respondWithCart($cart);
    }

    public function updateItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->resolveCart($request);
        $product = Product::findOrFail($validated['product_id']);

        try {
            $this->cartService->updateQuantity($cart, $product, $validated['quantity']);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return $this->respondWithCart($cart);
    }

    public function removeItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $cart = $this->resolveCart($request);
        $product = Product::findOrFail($validated['product_id']);

        $this->cartService->removeItem($cart, $product);

        return $this->respondWithCart($cart);
    }

    private function resolveCart(Request $request)
    {
        $userId = $request->user()?->id;
        $guestToken = $request->header('X-Cart-Token');

        return $this->cartService->getOrCreateCart($userId, $guestToken);
    }

    private function respondWithCart($cart)
    {
        $cart->load('items.product.attachments');

        return (new CartResource($cart))
            ->additional(['meta' => ['cart_token' => $cart->guest_token]])
            ->response()
            ->header('X-Cart-Token', $cart->guest_token ?? '');
    }
}
