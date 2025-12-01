<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Order\Models\Order;
use Modules\Payment\PayBuddy;
use Modules\Product\Models\Product;

class CheckoutController
{
    public function __invoke(CheckoutRequest $request): JsonResponse
    {
        $products = collect($request->input('products'))->map(function (array $productDetails) {
            return [
                'product' => Product::find($productDetails['id']),
                'quantity' => $productDetails['quantity'],
            ];
        });

        $orderTotalInCents = $products->sum(fn($productDetails) => $productDetails['quantity'] * $productDetails['product']->price_in_cents);

        $payBuddy = PayBuddy::make();

        try {
            $charge = $payBuddy->charge($request->input('payment_token'), $orderTotalInCents, 'Laracasts');
        } catch (\RuntimeException) {
            throw ValidationException::withMessages([
                'payment_token' => 'We could not process your payment at this time. Please try again later.',
            ]);
        }

        $order = Order::query()->create([
            'payment_id' => $charge['id'],
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'total_in_cents' => $orderTotalInCents,
            'user_id' => $request->user()->id,
        ]);

        foreach ($products as $product) {
            $product['product']->decrement('stock');

            $order->lines()->create([
                'order_id' => $order->id,
                'product_id' => $product['product']->id,
                'quantity' => $product['quantity'],
                'price_in_cents' => $product['product']->price_in_cents,
            ]);
        }


        return response()->json([], 201);
    }
}
