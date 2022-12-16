<?php

namespace App\Concerns;

trait EnumHasValues
{
    /**
     * Get the array representation of enum's values.
     *
     * @return array<int|string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
