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
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $type = fake()->randomElement(BillingType::values());

        $billingableType = $this->billingable();

        $priceAmount = $billingableType === Project::class
            ? fake()->randomNumber(5)
            : fake()->randomNumber(3);

        return [
            'billingable_type' => $billingableType,
            'type' => $type,
            'price_amount' => $priceAmount,
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
