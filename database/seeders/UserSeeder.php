<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = User::factory();

        $factory->testUser()->create();
        $factory->count(30)->create();
    }
}
