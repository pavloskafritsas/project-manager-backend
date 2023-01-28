<?php

namespace Tests\Feature\Models\Billing;

use App\Models\Billing;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

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

        $project->billing->refresh();
    }
);

test(
    'authenticated user can delete billing',
    function () use ($mutationDelete) {
        login();

        $project = Project::factory()
            ->has(Billing::factory())
            ->has(Task::factory()->has(Billing::factory()))
            ->create();

        $value = $mutationDelete->generateResponse([
            'type' => $project->billing->type,
            'price_amount' => $project->billing->price_amount,
        ]);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $project->billing->id])
            ->assertJson($value);

        $project->billing->refresh();
    }
)->throws(ModelNotFoundException::class);

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
