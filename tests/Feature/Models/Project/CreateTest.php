<?php

namespace Tests\Feature\Models\Project;

use App\Models\Meta;
use App\Models\Project;
use App\Models\Task;

$mutationCreate = GraphQLHelper::MUTATION_CREATE_PROJECT;

test(
    'unauthorized user cannot create project',
    function () use ($mutationCreate) {
        $input = Project::factory()->definition();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreate->operation(), ['input' => $input])
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authorized user can create project',
    function () use ($mutationCreate) {
        login();

        $input = Project::factory()->definition();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreate->operation(), ['input' => $input])
            ->assertJson($mutationCreate->generateResponse($input));
    }
);

test(
    'cannot create project if the name has already been taken',
    function () use ($mutationCreate) {
        login();

        /** @var Project $project */
        $project = Project::factory()->create();

        $input = ['name' => $project->name] + Project::factory()->definition();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreate->operation(), ['input' => $input])
            ->assertGraphQLValidationError('input.name', 'The input.name has already been taken.');
    }
);

test(
    'can create project with metas and tasks',
    function () use ($mutationCreate) {
        login();

        $project = Project::factory()->definition();
        $meta = Meta::factory()->definition();
        $task = Task::factory()->definition();

        $input = [
            ...$project,
            'metas' => ['create' => [$meta]],
            'tasks' => ['create' => [$task]],
        ];

        $value = $mutationCreate->generateResponse([
            ...$project,
            'metas' => [['__typename' => 'Meta', ...$meta]],
            'tasks' => [['__typename' => 'Task', ...$task]],
        ]);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreate->operation(), ['input' => $input])
            ->assertJson($value);
    }
);
