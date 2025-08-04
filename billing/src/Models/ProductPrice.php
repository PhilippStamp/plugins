<?php

namespace Boy132\Billing\Models;

use Boy132\Billing\Enums\PriceInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stripe\StripeClient;

/**
 * @property int $id
 * @property ?string $stripe_id
 * @property string $name
 * @property int $cost
 * @property PriceInterval $interval_type
 * @property int $interval_value
 * @property int $product_id
 * @property Product $product
 */
class ProductPrice extends Model
{
    protected $fillable = [
        'stripe_id',
        'product_id',
        'name',
        'cost',
        'interval_type',
        'interval_value',
    ];

    protected function casts(): array
    {
        return [
            'interval_type' => PriceInterval::class,
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function sync(): void
    {
        /** @var StripeClient $stripeClient */
        $stripeClient = app(StripeClient::class);

        if (is_null($this->stripe_id)) {
            $stripePrice = $stripeClient->prices->create([
                'currency' => config('billing.currency'),
                'nickname' => $this->name,
                'product' => $this->product->stripe_id,
                'recurring' => [
                    'interval' => $this->interval_type->value,
                    'interval_count' => $this->interval_value,
                ],
                'unit_amount' => $this->cost,
            ]);

            $this->update([
                'stripe_id' => $stripePrice->id,
            ]);
        } else {
            $stripeClient->prices->update($this->stripe_id, [
                'currency' => config('billing.currency'),
                'nickname' => $this->name,
                'product' => $this->product->stripe_id,
                'recurring' => [
                    'interval' => $this->interval_type->value,
                    'interval_count' => $this->interval_value,
                ],
                'unit_amount' => $this->cost,
            ]);
        }
    }
}
