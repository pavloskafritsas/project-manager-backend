<?php

namespace Database\Factories;

use App\Enums\BillingType;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Billing>
 */
class BillingFactory extends Factory
{
    public function inputDefinition(): array
    {
        $type = fake()->randomElement(BillingType::values());

        $price_amount = fake()->randomElement([
            fake()->randomNumber(3, true),
            fake()->randomNumber(4, true),
            fake()->randomNumber(5, true),
        ]);

        return compact('type', 'price_amount');
    }

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            ...$this->inputDefinition(),
            'billingable_type' => $this->billingable(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function billingable(): string
    {
        return fake()->randomElement([
            Project::class,
            Task::class,
        ]);
    }
}
