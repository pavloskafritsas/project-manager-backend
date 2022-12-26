<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Billing
 *
 * @property int $id
 * @property string $billingable_type
 * @property int $billingable_id
 * @property string $type
 * @property int $price_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $billingable
 *
 * @method static \Database\Factories\BillingFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Billing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Billing query()
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereBillingableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereBillingableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing wherePriceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Billing extends Model
{
    use HasFactory;

    /**
     * Get the parent billingable model (project or task).
     *
     * @return MorphTo<Project|Task, Billing>
     */
    public function billingable(): MorphTo
    {
        /**
         * Excluding this line from phpstan until the issue gets resoved.
         *
         * @see https://github.com/nunomaduro/larastan/issues/1223
         */
        return $this->morphTo(); /* @phpstan-ignore-line */
    }
}
