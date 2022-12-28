<?php

namespace Tests\Feature\Models\Billing;

use App\Models\Billing;
use App\Models\Project;
use App\Models\Task;
use Tests\TestCase;

$mutationCreateProjectBilling = GraphQLHelper::MUTATION_CREATE_PROJECT_BILLING;
$mutationCreateTaskBilling = GraphQLHelper::MUTATION_CREATE_TASK_BILLING;

test(
    'unauthorized user cannot create billing',
    function () use ($mutationCreateProjectBilling, $mutationCreateTaskBilling) {
        $task = Task::factory()->create();
        assert($task instanceof Task);

        $input = Billing::factory()->inputDefinition();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreateProjectBilling->operation(), [
                'billingable_id' => $task->project_id,
                'input' => $input,
            ])
            ->assertGraphQLErrorMessage('Unauthenticated.');

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreateTaskBilling->operation(), [
                'billingable_id' => $task->id,
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

        $input = Billing::factory()->inputDefinition();

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

        $task = Task::factory()->create();

        $input = Billing::factory()->inputDefinition();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreateTaskBilling->operation(), [
                'billingable_id' => $task->id,
                'input' => $input,
            ])
            ->assertJson($mutationCreateTaskBilling->generateResponse($input));
    }
);
