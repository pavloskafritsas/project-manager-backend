<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class TimeEntry extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date:Y-m-d',
        'end_time' => 'datetime:H:i:s',
        'start_time' => 'datetime:H:i:s',
    ];

    /**
     * The task the model belongs to.
     *
     * @return BelongsTo<Task,TimeEntry>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            /**
             * Excluding this line from phpstan until the feature gets implemented.
             *
             * @see https://github.com/phpstan/phpstan/issues/6430
             */
            get: fn (?string $value, array $attributes): ?string => $value /* @phpstan-ignore-line */
                ?? (isset($attributes['start_time'], $attributes['end_time'])
                    ? $this->calcDurationFromTimeInterval()
                    : null),
        );
    }

    public function calcDurationFromTimeInterval(): string
    {
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);
        $timestamp = $startTime->diffInSeconds($endTime, true);

        return gmdate('H:i:s', $timestamp);
    }
}
