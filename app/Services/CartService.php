<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Str;


class CartService
{
    public function getOrCreateCart(?int $userId, ?string $guestToken): Cart
    {
        if ($userId) {
            return Cart::firstOrCreate(['user_id' => $userId]);
        }

        if ($guestToken) {
            $cart = Cart::where('guest_token', $guestToken)->first();
            if ($cart) {
                return $cart;
            }
        }

        return Cart::create(['guest_token' => (string) Str::uuid()]);
    }

    public function addItem(Cart $cart, Product $product, int $quantity): CartItem
    {
        $existingItem = $cart->items()->where('product_id', $product->id)->first();
        $newQuantity = $existingItem ? $existingItem->quantity + $quantity : $quantity;

        $this->assertInStock($product, $newQuantity);

        if ($existingItem) {
            $existingItem->update(['quantity' => $newQuantity]);
            return $existingItem;
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(Cart $cart, Product $product, int $quantity): CartItem
    {
        $item = $cart->items()->where('product_id', $product->id)->firstOrFail();

        $this->assertInStock($product, $quantity);

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    public function removeItem(Cart $cart, Product $product): void
    {
        $cart->items()->where('product_id', $product->id)->delete();
    }

    public function getTotal(Cart $cart): float
    {
        return $cart->items->sum(fn (CartItem $item) => $item->product->current_price * $item->quantity);
    }

    private function assertInStock(Product $product, int $quantity): void
    {
        if ($quantity > $product->amount_in_stock) {
            throw new \RuntimeException("Only {$product->amount_in_stock} of {$product->title} available.");
        }
    }

    public function mergeGuestCartIntoUserCart(?string $guestToken, int $userId): void
    {
        if (!$guestToken) {
            return;
        }

        $guestCart = Cart::where('guest_token', $guestToken)->first();

        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        foreach ($guestCart->items as $guestItem) {
            $existingItem = $userCart->items()->where('product_id', $guestItem->product_id)->first();

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $guestItem->quantity;
                $product = $guestItem->product;
                $existingItem->update([
                    'quantity' => min($newQuantity, $product->amount_in_stock),
                ]);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity' => min($guestItem->quantity, $guestItem->product->amount_in_stock),
                ]);
            }
        }

        $guestCart->delete();
    }
}
