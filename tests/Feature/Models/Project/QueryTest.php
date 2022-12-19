<?php

namespace Tests\Feature\Models\Project;

use App\Models\Meta;
use App\Models\Project;
use App\Models\Task;

$mutationQueryProject = GraphQLHelper::QUERY_PROJECTS;

test('authorized user can list projects', function () use ($mutationQueryProject) {
    login();

    /** @var Collection<Project> */
    $projectList = Project::factory()
        ->hasMetas(Meta::factory()->count(1))
        ->hasTasks(Task::factory()->count(1))
        ->count(1)
        ->create();

    $value = $mutationQueryProject->generateResponse([
        'data' => $projectList->map(fn (Project $project) => [
            '__typename' => 'Project',
            ...$project->only(['id', 'name']),
            'metas' => $project->metas->map(fn (Meta $meta) => [
                '__typename' => 'Meta',
                ...$meta->only(['attribute', 'value', 'type']),
            ])->toArray(),
            'tasks' => $project->tasks->map(fn (Task $task) => [
                '__typename' => 'Task',
                ...$task->only(['name', 'description', 'priority', 'status']),
            ])->toArray(),
        ])->toArray(),
    ], false);

    /** @var TestCase $this */
    $this->graphQL($mutationQueryProject->operation())->assertJson($value);
});

test('unauthorized user cannot list projects', function () use ($mutationQueryProject) {
    /** @var Collection<Project> */
    $projectList = Project::factory()
        ->hasMetas(Meta::factory()->count(1))
        ->hasTasks(Task::factory()->count(1))
        ->count(1)
        ->create();

    /** @var TestCase $this */
    $this
        ->graphQL($mutationQueryProject->operation())
        ->assertGraphQLErrorMessage('Unauthenticated.');
});
