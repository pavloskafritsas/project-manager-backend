<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    public function inputDefinition(): array
    {
        return [
            ...fake()->randomElement([
                $this->getDuration(),
                $this->getTimeInterval(),
            ]),
            'date' => fake()->date(),
            'note' => fake()->randomElement([fake()->text(), null]),
        ];
    }

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            ...$this->inputDefinition(),
            'task_id' => Task::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function timeInterval(): self
    {
        return $this->state(function (array $attributes) {
            return $this->getTimeInterval();
        });
    }

    public function duration(): self
    {
        return $this->state(function (array $attributes) {
            return $this->getDuration();
        });
    }

    public function getDuration(): array
    {
        $duration = fake()->time();

        return compact('duration');
    }

    public function getTimeInterval(): array
    {
        $end_time = fake()->time();
        $start_time = fake()->time('H:i:s', now()->setTimeFromTimeString($end_time));

        return compact('start_time', 'end_time');
    }
}
