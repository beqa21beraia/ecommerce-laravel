<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService
    ) {}

    public function index(Request $request)
    {
        return OrderResource::collection(
            $this->orderService->list($request->user())
        );
    }

    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items');

        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'required|integer|exists:addresses,id',
        ]);

        $user = $request->user();
        $address = Address::findOrFail($validated['address_id']);

        if ($address->user_id !== $user->id) {
            abort(403, 'This address does not belong to you.');
        }

        $cart = $this->cartService->getOrCreateCart($user->id, null);

        try {
            $order = $this->orderService->checkout($user, $cart, $address);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $order->load('items');

        return new OrderResource($order);
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        try {
            $this->orderService->confirmPayment($order);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return new OrderResource($order);
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order);

        try {
            $this->orderService->cancel($order);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return new OrderResource($order);
    }
}
