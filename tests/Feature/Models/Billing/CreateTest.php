<?php

namespace Tests\Feature\Models\Billing;

use App\Models\Billing;
use App\Models\Project;
use App\Models\Task;

$mutationCreateProjectBilling = GraphQLHelper::MUTATION_CREATE_PROJECT_BILLING;
$mutationCreateTaskBilling = GraphQLHelper::MUTATION_CREATE_TASK_BILLING;

function getInputFromDefinition(array $definition): array
{
    return collect($definition)->only(['type', 'price_amount'])->toArray();
}

test(
    'unauthorized user cannot create billing',
    function () use ($mutationCreateProjectBilling, $mutationCreateTaskBilling) {
        $project = Project::factory()->has(Task::factory())->create();

        $input = getInputFromDefinition(Billing::factory()->definition());

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreateProjectBilling->operation(), [
                'billingable_id' => $project->id,
                'input' => $input,
            ])
            ->assertGraphQLErrorMessage('Unauthenticated.');

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreateTaskBilling->operation(), [
                'billingable_id' => $project->tasks->first()->id,
                'input' => $input,
            ])
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authorized user can create project billing',
    function () use ($mutationCreateProjectBilling) {
        login();

        $project = Project::factory()->create();

        $input = getInputFromDefinition(Billing::factory()->definition());

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreateProjectBilling->operation(), [
                'billingable_id' => $project->id,
                'input' => $input,
            ])
            ->assertJson($mutationCreateProjectBilling->generateResponse($input));
    }
);

test(
    'authorized user can create task billing',
    function () use ($mutationCreateTaskBilling) {
        login();

        $project = Project::factory()->has(Task::factory())->create();

        $input = getInputFromDefinition(Billing::factory()->definition());

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreateTaskBilling->operation(), [
                'billingable_id' => $project->tasks->first()->id,
                'input' => $input,
            ])
            ->assertJson($mutationCreateTaskBilling->generateResponse($input));
    }
);
