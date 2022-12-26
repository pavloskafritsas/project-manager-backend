<?php

namespace Tests\Feature\Models\Billing;

use App\Models\Billing;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

$mutationQuery = GraphQLHelper::QUERY_PROJECTS;

test(
    'unauthorized user cannot list billings',
    function () use ($mutationQuery) {
        /** @var TestCase $this */
        $this
            ->graphQL($mutationQuery->operation())
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authorized user can list project with billings',
    function () use ($mutationQuery) {
        login();

        /** @var Collection<Project> */
        $project = Project::factory()
            ->has(Billing::factory())
            ->has(Task::factory()->has(Billing::factory()))
            ->create();

        $billingProject = collect($project->billing->toArray())
            ->only(['type', 'price_amount']);

        $billingTask = collect($project->tasks->first()->billing->toArray())
            ->only(['type', 'price_amount']);

        $value = $mutationQuery->generateResponse(
            [
                'data' => [
                    [
                        'billing' => [
                            '__typename' => 'Billing',
                            ...$billingProject,
                        ],
                        'tasks' => [
                            [
                                'billing' => [
                                    '__typename' => 'Billing',
                                    ...$billingTask,
                                ],
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
