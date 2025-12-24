<?php

namespace Modules\Order;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Order\Infrastructure\Database\Factories\OrderFactory;
use Modules\Payment\Payment;
use Modules\Product\Collections\CartItemCollection;
use Modules\Product\DTOs\CartItem;

class Order extends Model
{
    use HasFactory;

    public const PENDING = 'pending';
    public const COMPLETED = 'completed';

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
        return $this->hasMany(OrderLine::class);
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

    public static function startForUser($userId): self
    {
        return self::make([
            'user_id' => $userId,
            'status' => self::PENDING,
        ]);
    }

    /**
     * @param CartItemCollection<CartItem> $items
     * @return void
     */
    public function addLinesFromCartItems(CartItemCollection $items): void
    {
        foreach ($items->items() as $item) {
             $this->lines->push(OrderLine::make([
                'product_id' => $item->product->id,
                'price_in_cents' => $item->product->priceInCents,
                'quantity' => $item->quantity,

            ]));
        }

        $this->total_in_cents = $this->lines->sum(fn(OrderLine $line) => $line->price_in_cents);
    }

    /**
     * @return void
     * @throws OrderMissingOrderLinesException
     */
    public function fulfill(): void
    {
        if ($this->lines->isEmpty()) {
            throw new OrderMissingOrderLinesException();
        }

        $this->status = self::COMPLETED;
        $this->save();
        $this->lines()->saveMany($this->lines);
    }

    public function localizedTotal()
    {
        return $this->total_in_cents;
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
