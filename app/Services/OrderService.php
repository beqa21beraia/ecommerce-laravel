<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function checkout(User $user, Cart $cart, Address $address): Order
    {
        if ($cart->items->isEmpty()) {
            throw new \RuntimeException('Cannot checkout an empty cart.');
        }

        return DB::transaction(function () use ($user, $cart, $address) {
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'city' => $address->city,
                'address_line' => $address->address_line,
                'postal_code' => $address->postal_code,
                'status' => OrderStatus::Pending,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($cart->items as $cartItem) {
                $product = Product::where('id', $cartItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product || $product->amount_in_stock < $cartItem->quantity) {
                    throw new \RuntimeException(
                        "'{$cartItem->product->title}' no longer has enough stock."
                    );
                }

                $unitPrice = $product->current_price;
                $lineTotal = $unitPrice * $cartItem->quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'unit_price' => $unitPrice,
                    'quantity' => $cartItem->quantity,
                    'line_total' => $lineTotal,
                ]);

                $product->decrement('amount_in_stock', $cartItem->quantity);

                $total += $lineTotal;
            }

            $order->update(['total' => $total]);

            $cart->items()->delete();

            return $order;
        });
    }

    public function confirmPayment(Order $order): Order
    {
        if (!$order->status->canTransitionTo(OrderStatus::Confirmed)) {
            throw new \RuntimeException("Order cannot be confirmed from its current status.");
        }

        $order->update(['status' => OrderStatus::Confirmed]);

        return $order;
    }

    public function cancel(Order $order): Order
    {
        if (!$order->status->canTransitionTo(OrderStatus::Cancelled)) {
            throw new \RuntimeException("Order cannot be cancelled from its current status.");
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('amount_in_stock', $item->quantity);
                }
            }

            $order->update(['status' => OrderStatus::Cancelled]);
        });

        return $order;
    }

    public function list(User $user)
    {
        return $user->orders()->with('items')->latest()->get();
    }
}
