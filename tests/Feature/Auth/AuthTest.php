<?php

use Tests\Feature\Auth\AuthOperation;

test('can return the currently authenticated user', function () {
    $this
        ->graphQL(AuthOperation::QUERY_ME)
        ->assertExactJson(['data' => ['me' => null]]);

    $res = login();

    $this
        ->graphQL(AuthOperation::QUERY_ME)
        ->assertExactJson(['data' => ['me' => $res['data']['login']]]);
});
