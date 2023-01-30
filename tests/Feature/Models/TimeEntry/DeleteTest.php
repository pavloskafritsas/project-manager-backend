<?php

namespace Tests\Feature\Models\TimeEntry;

use App\Models\TimeEntry;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

$mutationDelete = GraphQLHelper::MUTATION_DELETE_TIME_ENTRY;

test(
    'unauthenticated user cannot delete time entry',
    function () use ($mutationDelete) {
        $timeEntry = TimeEntry::factory()->create();
        assert($timeEntry instanceof TimeEntry);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $timeEntry->id])
            ->assertGraphQLErrorMessage('Unauthenticated.');

        $timeEntry->refresh();
    }
);

test(
    'authenticated user can delete time entry',
    function () use ($mutationDelete) {
        login();

        $timeEntry = TimeEntry::factory()->create();
        assert($timeEntry instanceof TimeEntry);

        $response = collect($timeEntry->getAttributes())
            ->only([
                'date',
                'start_time',
                'end_time',
                'duration',
                'note',
            ])
            ->toArray();

        $value = $mutationDelete->generateResponse($response);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $timeEntry->id])
            ->assertJson($value);

        $timeEntry->refresh();
    }
)->throws(ModelNotFoundException::class);

test(
    'cannot delete time entry with invalid id',
    function () use ($mutationDelete) {
        login();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => 0])
            ->assertGraphQLValidationError('id', 'The selected id is invalid.');
    }
);
