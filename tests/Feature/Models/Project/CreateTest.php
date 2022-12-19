<?php

namespace Tests\Feature\Models\Project;

use App\Models\Meta;
use App\Models\Project;
use App\Models\Task;

$mutationCreateProject = GraphQLHelper::MUTATION_CREATE_PROJECT;

test('unauthorized user cannot create project', function () use ($mutationCreateProject) {
    $input = Project::factory()->definition();

    /** @var TestCase $this */
    $this
        ->graphQL($mutationCreateProject->operation(), ['input' => $input])
        ->assertGraphQLErrorMessage('Unauthenticated.');
});

test('authorized user can create project', function () use ($mutationCreateProject) {
    login();

    $input = Project::factory()->definition();

    /** @var TestCase $this */
    $this
        ->graphQL($mutationCreateProject->operation(), ['input' => $input])
        ->assertJson($mutationCreateProject->generateResponse($input));
});

test('cannot create project if the name has already been taken', function () use ($mutationCreateProject) {
    login();

    /** @var Project $project */
    $project = Project::factory()->create();

    $input = ['name' => $project->name] + Project::factory()->definition();

    /** @var TestCase $this */
    $this
        ->graphQL($mutationCreateProject->operation(), ['input' => $input])
        ->assertGraphQLValidationError('input.name', 'The input.name has already been taken.');
});

test('can create project with metas and tasks', function () use ($mutationCreateProject) {
    login();

    $project = Project::factory()->definition();
    $meta = Meta::factory()->definition();
    $task = Task::factory()->definition();

    $input = [
        ...$project,
        'metas' => ['create' => [$meta]],
        'tasks' => ['create' => [$task]],
    ];

    $value = $mutationCreateProject->generateResponse([
        ...$project,
        'metas' => [['__typename' => 'Meta', ...$meta]],
        'tasks' => [['__typename' => 'Task', ...$task]],
    ]);

    /** @var TestCase $this */
    $this
        ->graphQL($mutationCreateProject->operation(), ['input' => $input])
        ->assertJson($value);
});
