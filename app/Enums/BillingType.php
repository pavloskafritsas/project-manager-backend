<?php

namespace App\Enums;

use App\Concerns\EnumHasValues;

enum BillingType: string
{
    use EnumHasValues;

    case FLAT = 'FLAT';
    case RATE = 'RATE';
}
