<?php

namespace App\Enums;

use App\Concerns\EnumHasValues;

enum MetaType: string
{
    use EnumHasValues;

    case TEXT = 'TEXT';
    case URL = 'URL';
}
