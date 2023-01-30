<?php

namespace Tests\Feature\Models\TimeEntry;

enum GraphQLHelper
{
    case MUTATION_CREATE_TIME_ENTRY;
    case MUTATION_UPDATE_TIME_ENTRY;
    case MUTATION_DELETE_TIME_ENTRY;
    case QUERY_TIME_ENTRIES;

    public function getTypename(): array
    {
        return ['__typename' => 'TimeEntry'];
    }

    public function generateResponse(?array $response = null, bool $mergeTypename = true): array
    {
        if (is_array($response) && $mergeTypename) {
            $response += $this->getTypeName();
        }

        return match ($this) {
            self::MUTATION_CREATE_TIME_ENTRY => ['data' => ['createTimeEntry' => $response]],
            self::MUTATION_UPDATE_TIME_ENTRY => ['data' => ['updateTimeEntry' => $response]],
            self::MUTATION_DELETE_TIME_ENTRY => ['data' => ['deleteTimeEntry' => $response]],
        };
    }

    public function operation(): string
    {
        return match ($this) {
            self::MUTATION_CREATE_TIME_ENTRY =>
            /** @lang GraphQL */
            '
            mutation($task_id: ID!, $input: CreateTimeEntryInput!)
            {
                createTimeEntry(task_id: $task_id, input: $input) {
                    __typename
                    id
                    date
                    start_time
                    end_time
                    duration
                    note
                }
            }
            ',
            self::MUTATION_DELETE_TIME_ENTRY =>
            /** @lang GraphQL */
            '
            mutation($id: ID!)
            {
                deleteTimeEntry(id: $id) {
                    __typename
                    id
                    date
                    start_time
                    end_time
                    duration
                    note
                }
            }
            ',
            self::MUTATION_UPDATE_TIME_ENTRY =>
            /** @lang GraphQL */
            '
            mutation($input: UpdateTimeEntryInput!)
            {
                updateTimeEntry(input: $input) {
                    __typename
                    id
                    date
                    start_time
                    end_time
                    duration
                    note
                }
            }
            ',
        };
    }
}
