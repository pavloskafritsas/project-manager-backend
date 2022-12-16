<?php

namespace Database\Factories;

use App\Enums\MetaType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meta>
 */
class MetaFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
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
}
