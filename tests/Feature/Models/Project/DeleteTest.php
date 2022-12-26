<?php

namespace Tests\Feature\Models\Project;

use App\Models\Project;

$mutationDelete = GraphQLHelper::MUTATION_DELETE_PROJECT;

test(
    'unauthenticated user cannot delete project',
    function () use ($mutationDelete) {
        $project = Project::factory()->create();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $project->id])
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authenticated user can delete project',
    function () use ($mutationDelete) {
        $project = Project::factory()->create();

        login();

        $value = $mutationDelete->generateResponse(['name' => $project->name]);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $project->id])
            ->assertJson($value);
    }
);

test(
    'cannot delete project with invalid id',
    function () use ($mutationDelete) {
        login();

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => 0])
            ->assertGraphQLValidationError('id', 'The selected id is invalid.');
    }
);
