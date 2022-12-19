<?php

namespace Tests\Feature\Auth;

use App\Models\User;

test('can login', function () {
    $loginMutation = GraphQLHelper::MUTATION_LOGIN;

    $user = User::factory()->createOne();

    $user->email = 'invalid@mail.com';

    $res = login($user);

    $res->assertGraphQLErrorMessage(__('auth.failed'));

    $user->refresh();

    $res = login($user);

    $res->assertJson($loginMutation->generateResponse($user->only(['id', 'name', 'email'])));
});
