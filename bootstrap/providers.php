<?php

return [
    App\Providers\AppServiceProvider::class,
    \Modules\Order\Infrastructure\Providers\OrderServiceProvider::class,
    \Modules\Payment\Infrastructure\Providers\PaymentServiceProvider::class,
    \Modules\Product\Providers\ProductServiceProvider::class,
    \Modules\Shipment\Providers\ShipmentServiceProvider::class,
];
