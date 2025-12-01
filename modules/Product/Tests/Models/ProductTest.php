<?php

namespace Modules\Product\Tests\Models;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Modules\Product\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_creates_a_product(): void
    {
//        $product = new Product();
        $product = Product::factory()->create();

        $this->assertTrue(true);
    }
}
