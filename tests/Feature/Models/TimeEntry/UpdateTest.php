<?php

namespace Tests\Feature\Models\TimeEntry;

use App\Models\TimeEntry;
use Tests\TestCase;

$mutationUpdate = GraphQLHelper::MUTATION_UPDATE_TIME_ENTRY;

test(
    'unauthorized user cannot update time entries',
    function () use ($mutationUpdate) {
        $timeEntry = TimeEntry::factory()->create();

        $input = TimeEntry::factory()->inputDefinition();

        $variables = [
            'input' => [
                'id' => $timeEntry->id,
                ...$input,
            ],
        ];

        /** @var TestCase $this */
        $this
            ->graphQL($mutationUpdate->operation(), $variables)
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authorized user can update time entries',
    function () use ($mutationUpdate) {
        login();

        $timeEntry = TimeEntry::factory()->create();

        $input = TimeEntry::factory()->inputDefinition();

        $variables = [
            'input' => [
                'id' => $timeEntry->id,
                ...$input,
            ],
        ];

        /** @var TestCase $this */
        $this
            ->graphQL($mutationUpdate->operation(), $variables)
            ->assertJson($mutationUpdate->generateResponse($input));
    }
);
