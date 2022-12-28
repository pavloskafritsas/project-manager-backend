<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function inputDefinition(): array
    {
        $start_at = fake()->randomElement([fake()->date(), null]);

        $end_at = $start_at
            ? fake()->randomElement([
                Carbon::parse($start_at)->addDays(fake()->randomNumber(2))->format('Y-m-d'),
                $start_at,
                null,
            ])
            : null;

        return [
            ...compact('start_at', 'end_at'),
            'name' => fake()->word(),
            'description' => fake()->boolean() ? fake()->text() : null,
            'priority' => fake()->randomElement(Priority::values()),
            'status' => fake()->randomElement(Status::values()),
        ];
    }

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            ...$this->inputDefinition(),
            'project_id' => Project::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
