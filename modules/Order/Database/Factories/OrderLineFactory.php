<?php

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Models\OrderLines;
use Modules\Product\Factories\ProductFactory;

class OrderLineFactory extends Factory
{
    protected $model = OrderLines::class;

    public function definition(): array
    {
        return [
            'order_id' => OrderFactory::new(),
            'product_id' => ProductFactory::new(),
            'product_price_in_cents' => $this->faker->randomFloat(2, 1, 100),
            'quantity' => 1,
        ];
    }
}
