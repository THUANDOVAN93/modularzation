<?php

namespace Modules\Product\Tests\Models;

use Modules\Product\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_it_creates_a_product(): void
    {
        $product = new Product();

        dd($product);
    }
}
