<?php

namespace App\Concerns;

use App\Models\Billing;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasBilling
{
    /**
     * Get the models's billing.
     *
     * @return MorphOne<Billing>
     */
    public function billing(): MorphOne
    {
        return $this->morphOne(Billing::class, 'billingable');
    }
}
