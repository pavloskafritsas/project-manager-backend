<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $from = fake()->randomElement() ? fake()->date() : null;

        $to = $from
            ? (fake()->boolean()
                ? Carbon::parse($from)->addDays(fake()->randomNumber(2))->format('Y-m-d')
                : null)
            : null;

        return [
            'name' => fake()->word(),
            'description' => fake()->boolean() ? fake()->text() : null,
            'priority' => fake()->randomElement(Priority::values()),
            'status' => fake()->randomElement(Status::values()),
            'from' => $from,
            'to' => $to,
        ];
    }
}
