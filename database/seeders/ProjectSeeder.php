<?php

namespace Database\Seeders;

use App\Models\Meta;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()
            ->has(Meta::factory()->count(5))
            ->has(Task::factory()->count(15))
            ->count(30)
            ->create();
    }
}
