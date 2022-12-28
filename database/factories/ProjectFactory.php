<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function inputDefinition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            ...$this->inputDefinition(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
