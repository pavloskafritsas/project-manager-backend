<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\Meta;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()
            ->has(Billing::factory())
            ->has(Meta::factory()->count(5))
            ->has(Task::factory()
                ->has(Billing::factory())
                ->has(TimeEntry::factory()->count(5))
                ->count(15))
            ->count(20)
            ->create();
    }
}
