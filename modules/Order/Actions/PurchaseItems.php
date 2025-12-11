<?php

namespace Modules\Order\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
use Modules\Order\Events\OrderFulfilled;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $eventDispatcher,
    )
    {
    }

    /**
     * @throws \Throwable
     */
    public function handle(CartItemCollection $items, PayBuddy $paymentProvider, string $paymentToken, int $userId, string $userEmail): Order
    {
        return $this->databaseManager->transaction(function () use ($items, $paymentProvider, $paymentToken, $userId, $userEmail) {

            $order = Order::startForUser($userId);
            $order->addLinesFromCartItems($items);
            $order->fulfill();

            foreach ($items->items() as $cartItem) {
                $this->productStockManager->decrement($cartItem->product->id, $cartItem->quantity);
            }

            $this->createPaymentForOrder->handle(
                $order->id,
                $userId,
                $items->totalInCents(),
                $paymentProvider,
                $paymentToken
            );

            $this->eventDispatcher->dispatch(
                new OrderFulfilled(
                    orderId: $order->id,
                    totalInCents: $items->totalInCents(),
                    localizedTotal: $order->localizedTotal(),
                    userId: $userId,
                    userEmail: $userEmail,
                )
            );

            return $order;
        });
    }
}
