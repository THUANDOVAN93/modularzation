<?php

namespace Modules\Order\Events;

class OrderFulfilled
{
    public function __construct(
        public int $orderId,
        public int $totalInCents,
        public string $localizedTotal,
        public int $userId,
        public string $userEmail,
    )
    {
    }
}
