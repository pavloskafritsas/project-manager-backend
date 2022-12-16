<?php

use App\Models\User;

test('can login', function () {
    $user = User::factory()->createOne();

    $res = login($user);

    $data = [
        'data' => [
            'login' => ['__typename' => 'User'] + $user->only(['id', 'name', 'email']),
        ],
    ];

    $res->assertJson($data);
});
