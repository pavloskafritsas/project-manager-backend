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
        $start_at = fake()->randomElement() ? fake()->date() : null;

        $end_at = $start_at
            ? (fake()->boolean()
                ? Carbon::parse($start_at)->addDays(fake()->randomNumber(2))->format('Y-m-d')
                : null)
            : null;

        return [
            'name' => fake()->word(),
            'description' => fake()->boolean() ? fake()->text() : null,
            'priority' => fake()->randomElement(Priority::values()),
            'status' => fake()->randomElement(Status::values()),
            'start_at' => $start_at,
            'end_at' => $end_at,
        ];
    }
}
