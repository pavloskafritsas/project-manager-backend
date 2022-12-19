<?php

namespace Tests\Feature\Auth;

test('can return the currently authenticated user', function () {
    $queryMe = GraphQLHelper::QUERY_ME;

    $this
        ->graphQL($queryMe->operation())
        ->assertExactJson($queryMe->generateResponse());

    $res = login();

    $this
        ->graphQL($queryMe->operation())
        ->assertExactJson(['data' => ['me' => $res['data']['login']]]);
});
