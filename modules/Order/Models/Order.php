<?php

namespace Modules\Order\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Order\Database\Factories\OrderFactory;
use Modules\Payment\Payment;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'payment_id',
        'payment_gateway',
        'total_in_cents',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'total_in_cents' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLines::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function lastPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function url(): string
    {
        return route('orders.show', $this);
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
