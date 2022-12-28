<?php

namespace Tests\Feature\Models\Project;

use App\Models\Meta;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertTrue;
use Tests\TestCase;

$mutationUpdate = GraphQLHelper::MUTATION_UPDATE_PROJECT;

test(
    'unauthorized user cannot update project',
    function () use ($mutationUpdate) {
        $project = Project::factory()->create();

        $variables = [
            'input' => [
                'id' => $project->id,
                'name' => 'Updated name',
            ],
        ];

        /** @var TestCase $this */
        $this
            ->graphQL($mutationUpdate->operation(), $variables)
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authorized user can update project',
    function () use ($mutationUpdate) {
        login();

        $project = Project::factory()->create();

        $variables = [
            'input' => [
                'id' => $project->id,
                'name' => 'Updated name',
            ],
        ];

        $value = ['name' => 'Updated name'];

        /** @var TestCase $this */
        $this
            ->graphQL($mutationUpdate->operation(), $variables)
            ->assertJson($mutationUpdate->generateResponse($value));
    }
);

test(
    'can update project, metas and tasks',
    function () use ($mutationUpdate) {
        login();

        $project = Project::factory()
            ->has(Meta::factory()->count(3))
            ->has(Task::factory()->count(2))
            ->create();

        $metas = Meta::query()
            ->get(['id', 'attribute', 'value'])
            ->map(fn (Meta $meta) => [
                'id' => $meta->id,
                'attribute' => "updated_attribute_{$meta->id}",
                'value' => "updated_value_{$meta->id}",
            ]);

        $tasks = Task::query()
            ->get(['id', 'name'])
            ->map(fn (Task $task) => [
                'id' => $task->id,
                'name' => "updated_name_{$task->id}",
            ]);

        $variables = [
            'input' => [
                'id' => $project->id,
                'name' => $project->name,
                'metas' => ['update' => $metas->toArray()],
                'tasks' => ['update' => $tasks->toArray()],
            ],
        ];

        $value = $mutationUpdate->generateResponse([
            ...$project->only(['name']),
            'metas' => $metas->map(fn (array $meta) => [
                '__typename' => 'Meta',
                'attribute' => $meta['attribute'],
                'value' => $meta['value'],
            ])->toArray(),
            'tasks' => $tasks->map(fn (array $task) => [
                '__typename' => 'Task',
                'name' => $task['name'],
            ])->toArray(),
        ]);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationUpdate->operation(), $variables)
            ->assertJson($value);
    }
);

test('can delete project\'s metas and tasks', function () use ($mutationUpdate) {
    login();

    /** @var Collection<int, Project> $projects */
    $projects = Project::factory()
        ->has(Meta::factory()->count(3))
        ->has(Task::factory()->count(2))
        ->count(10)
        ->create();

    $project = $projects->first();
    assert($project instanceof Project);

    $metaIds = $project->metas->pluck('id');
    $taskIds = $project->tasks->pluck('id');

    $variables = [
        'input' => [
            'id' => $project->id,
            'metas' => ['delete' => $metaIds],
            'tasks' => ['delete' => $taskIds],
        ],
    ];

    /** @var TestCase $this */
    $this->graphQL($mutationUpdate->operation(), $variables);

    assertEmpty(Meta::findMany($metaIds));
    assertEmpty(Task::findMany($taskIds));

    assertTrue(27 === Meta::count());
    assertTrue(18 === Task::count());
});
