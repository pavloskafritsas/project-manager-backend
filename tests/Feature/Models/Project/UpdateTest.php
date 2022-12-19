<?php

namespace Tests\Feature\Models\Project;

use App\Models\Meta;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertTrue;
use Tests\TestCase;

$mutationUpdateProject = GraphQLHelper::MUTATION_UPDATE_PROJECT;

test('unauthorized user cannot update project', function () use ($mutationUpdateProject) {
    $project = Project::factory()->create();

    $variables = ['id' => $project->id, 'input' => ['name' => 'Updated name']];

    /** @var TestCase $this */
    $this
        ->graphQL($mutationUpdateProject->operation(), $variables)
        ->assertGraphQLErrorMessage('Unauthenticated.');
});

test('authorized user can update project', function () use ($mutationUpdateProject) {
    login();

    $project = Project::factory()->create();

    $variables = ['id' => $project->id, 'input' => ['name' => 'Updated name']];

    $value = ['name' => 'Updated name'];

    /** @var TestCase $this */
    $this
        ->graphQL($mutationUpdateProject->operation(), $variables)
        ->assertJson($mutationUpdateProject->generateResponse($value));
});

test('can update project, metas and tasks', function () use ($mutationUpdateProject) {
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
        'id' => $project->id,
        'name' => $project->name,
        'input' => [
            'metas' => ['update' => $metas->toArray()],
            'tasks' => ['update' => $tasks->toArray()],
        ],
    ];

    $value = $mutationUpdateProject->generateResponse([
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
        ->graphQL($mutationUpdateProject->operation(), $variables)
        ->assertJson($value);
});

test('can delete project\'s metas and tasks', function () use ($mutationUpdateProject) {
    login();

    /** @var Collection<int, Project> $projects */
    $projects = Project::factory()
        ->has(Meta::factory()->count(3))
        ->has(Task::factory()->count(2))
        ->count(10)
        ->create();

    /** @var Project $project */
    $project = $projects->first();

    $metaIds = $project->metas->pluck('id');
    $taskIds = $project->tasks->pluck('id');

    $variables = [
        'id' => $project->id,
        'input' => [
            'metas' => ['delete' => $metaIds],
            'tasks' => ['delete' => $taskIds],
        ],
    ];

    /** @var TestCase $this */
    $this->graphQL($mutationUpdateProject->operation(), $variables);

    assertEmpty(Meta::findMany($metaIds));
    assertEmpty(Task::findMany($taskIds));

    assertTrue(27 === Meta::count());
    assertTrue(18 === Task::count());
});
