<?php

namespace Tests\Feature\Models\Billing;

use App\Models\Billing;
use App\Models\Project;
use Tests\TestCase;

$mutationUpdate = GraphQLHelper::MUTATION_UPDATE_BILLING;

test(
    'unauthorized user cannot update billing',
    function () use ($mutationUpdate) {
        $project = Project::factory()
            ->has(Billing::factory())
            ->create();

        $variables = [
            'input' => [
                'id' => $project->billing->id,
                'price_amount' => 9999,
            ],
        ];

        /** @var TestCase $this */
        $this
            ->graphQL($mutationUpdate->operation(), $variables)
            ->assertGraphQLErrorMessage('Unauthenticated.');
    }
);

test(
    'authorized user can update billing',
    function () use ($mutationUpdate) {
        login();

        $project = Project::factory()
            ->has(Billing::factory())
            ->create();
        assert($project instanceof Project);
        assert($project->billing instanceof Billing);

        $value = ['price_amount' => 9999];

        $variables = [
            'input' => [
                'id' => $project->billing->id,
                ...$value,
            ],
        ];

        /** @var TestCase $this */
        $this
            ->graphQL($mutationUpdate->operation(), $variables)
            ->assertJson($mutationUpdate->generateResponse($value));
    }
);
