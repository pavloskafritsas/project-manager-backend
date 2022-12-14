<?php

use App\Models\User;

test('can logout', function () {
    $user = User::factory()->createOne();

    login($user);

    $res = logout($user);

    $value = [
        'data' => [
            'logout' => ['__typename' => 'User'] + $user->only(['id', 'name', 'email']),
        ],
    ];

    $res->assertJson($value);
});
