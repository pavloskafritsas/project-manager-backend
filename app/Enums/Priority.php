<?php

namespace App\Enums;

use App\Concerns\EnumHasValues;

enum Priority: string
{
    use EnumHasValues;

    case CRITICAL = 'CRITICAL';
    case HIGH = 'HIGH';
    case LOW = 'LOW';
    case NORMAL = 'NORMAL';
}
