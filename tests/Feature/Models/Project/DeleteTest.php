<?php

namespace Tests\Feature\Models\Project;

use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

$mutationDelete = GraphQLHelper::MUTATION_DELETE_PROJECT;

test(
    'unauthenticated user cannot delete project',
    function () use ($mutationDelete) {
        $project = Project::factory()->create();
        assert($project instanceof Project);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $project->id])
            ->assertGraphQLErrorMessage('Unauthenticated.');

        $project->refresh();
    }
);

test(
    'authenticated user can delete project',
    function () use ($mutationDelete) {
        login();

        $project = Project::factory()->create();
        assert($project instanceof Project);

        $value = $mutationDelete->generateResponse(['name' => $project->name]);

        /** @var TestCase $this */
        $this
            ->graphQL($mutationDelete->operation(), ['id' => $project->id])
            ->assertJson($value);

        $project->refresh();
    }
)->throws(ModelNotFoundException::class);

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
