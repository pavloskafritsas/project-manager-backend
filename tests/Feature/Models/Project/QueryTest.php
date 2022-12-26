<?php

namespace Tests\Feature\Models\Project;

use App\Models\Meta;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

$mutationQuery = GraphQLHelper::QUERY_PROJECTS;

test(
    'unauthorized user cannot list projects',
    function () use ($mutationQuery) {
        /** @var TestCase $this */
        $this
            ->graphQL($mutationQuery->operation())
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authorized user can list projects',
    function () use ($mutationQuery) {
        login();

        /** @var Collection<Project> */
        $project = Project::factory()
            ->hasMetas(Meta::factory())
            ->hasTasks(Task::factory())
            ->create();

        $value = $mutationQuery->generateResponse(
            [
                'data' => [
                    [
                        '__typename' => 'Project',
                        ...$project->only(['id', 'name']),
                        'metas' => [
                            [
                                '__typename' => 'Meta',
                                ...$project
                                    ->metas
                                    ->first()
                                    ->only(['attribute', 'value', 'type']),
                            ],
                        ],
                        'tasks' => [
                            [
                                '__typename' => 'Task',
                                ...$project
                                    ->tasks
                                    ->first()
                                    ->only(['name', 'description', 'priority', 'status']),
                            ],
                        ],
                    ],
                ],
            ],
            false
        );

        /** @var TestCase $this */
        $this->graphQL($mutationQuery->operation())->assertJson($value);
    }
);
