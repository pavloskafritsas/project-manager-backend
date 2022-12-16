<?php

namespace App\Enums;

use App\Concerns\EnumHasValues;

enum Status: string
{
    use EnumHasValues;

    case BACKLOG = 'BACKLOG';
    case CANCELLED = 'CANCELLED';
    case COMPLETED = 'COMPLETED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case TO_DO = 'TO_DO';
}
