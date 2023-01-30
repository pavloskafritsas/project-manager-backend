<?php

namespace Tests\Feature\Models\TimeEntry;

use App\Models\Task;
use App\Models\TimeEntry;
use Tests\TestCase;

$mutationCreate = GraphQLHelper::MUTATION_CREATE_TIME_ENTRY;

test(
    'unauthorized user cannot create time entry',
    function () use ($mutationCreate) {
        $task = Task::factory()->create();
        assert($task instanceof Task);

        $input = TimeEntry::factory()->inputDefinition();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreate->operation(), [
                'task_id' => $task->id,
                'input' => $input,
            ])
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authorized user can create time entry',
    function () use ($mutationCreate) {
        login();

        $task = Task::factory()->create();
        assert($task instanceof Task);

        $input = TimeEntry::factory()->inputDefinition();

        $value = $mutationCreate->generateResponse($input);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationCreate->operation(), [
                'task_id' => $task->id,
                'input' => $input,
            ])
            ->assertJson($value);
    }
);
