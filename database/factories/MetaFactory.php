<?php

namespace Database\Factories;

use App\Enums\MetaType;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meta>
 */
class MetaFactory extends Factory
{
    public function inputDefinition(): array
    {
        $type = fake()->randomElement(MetaType::values());

        return [
            'attribute' => fake()->word(),
            'value' => $type === 'URL'
                ? fake()->unique()->url()
                : fake()->unique()->word(),
            'type' => $type,
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
