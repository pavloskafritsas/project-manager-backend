<?php

namespace Tests\Feature\Models\Project;

use App\Models\Project;

$mutationDeleteProject = GraphQLHelper::MUTATION_DELETE_PROJECT;

test('unauthenticated user cannot delete project', function () use ($mutationDeleteProject) {
    $project = Project::factory()->create();

    /** @var TestCase $this */
    $this
        ->graphQL($mutationDeleteProject->operation(), ['id' => $project->id])
        ->assertGraphQLErrorMessage('Unauthenticated.');
});

test('authenticated user can delete project', function () use ($mutationDeleteProject) {
    $project = Project::factory()->create();

    login();

    $value = $mutationDeleteProject->generateResponse(['name' => $project->name]);

    /** @var TestCase $this */
    $this
        ->graphQL($mutationDeleteProject->operation(), ['id' => $project->id])
        ->assertJson($value);
});

test('cannot delete project with invalid id', function () use ($mutationDeleteProject) {
    login();

    /** @var TestCase $this */
    $this
        ->graphQL($mutationDeleteProject->operation(), ['id' => 0])
        ->assertGraphQLValidationError('id', 'The selected id is invalid.');
});
