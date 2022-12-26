<?php

namespace App\GraphQL\Mutations;

use App\Models\Billing;
use App\Models\Project;

final class CreateProjectBilling
{
    /**
     * @param  null  $_
     * @param  array{billingable_id: string, type: string, value: int}  $args
     */
    public function __invoke(null $_, array $args): Billing
    {
        $billing = (new Billing())->forceFill($args);

        $billing->billingable_type = Project::class;

        $billing->save();

        return $billing;
    }
}
