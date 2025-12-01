<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Database\Factories\OrderLineFactory;

class OrderLines extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'price_in_cents',
        'quantity',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'price_in_cents' => 'integer',
        'quantity' => 'integer',
    ];

    protected static function newFactory(): OrderLineFactory
    {
        return OrderLineFactory::new();
    }
}
