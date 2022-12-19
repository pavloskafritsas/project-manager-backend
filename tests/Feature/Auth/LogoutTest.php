<?php

namespace Tests\Feature\Auth;

use App\Models\User;

test('can logout', function () {
    $mutationLogout = GraphQLHelper::MUTATION_LOGOUT;

    $user = User::factory()->createOne();

    login($user);

    $res = logout($user);

    $res->assertJson($mutationLogout->generateResponse($user->only(['id', 'name', 'email'])));
});
