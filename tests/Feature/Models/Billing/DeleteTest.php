<?php

namespace Tests\Feature\Models\Billing;

use App\Models\Billing;
use App\Models\Project;
use App\Models\Task;

$mutationDelete = GraphQLHelper::MUTATION_DELETE_BILLING;

test(
    'unauthenticated user cannot delete billing',
    function () use ($mutationDelete) {
        $project = Project::factory()
            ->has(Billing::factory())
            ->create();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $project->billing->id])
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authenticated user can delete billing',
    function () use ($mutationDelete) {
        $project = Project::factory()
            ->has(Billing::factory())
            ->has(Task::factory()->has(Billing::factory()))
            ->create();

        login();

        $value = $mutationDelete->generateResponse([
            'type' => $project->billing->type,
            'price_amount' => $project->billing->price_amount,
        ]);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $project->billing->id])
            ->assertJson($value);
    }
);

test(
    'cannot delete billing with invalid id',
    function () use ($mutationDelete) {
        login();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => 0])
            ->assertGraphQLValidationError('id', 'The selected id is invalid.');
    }
);
