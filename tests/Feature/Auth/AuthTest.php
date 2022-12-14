<?php

test('can return the currently authenticated user', function () {
    $LOGIN_QUERY =
        /** @lang GraphQL */
        '
        {
            me {
                __typename
                id
                name
                email
            }
        }
    ';

    $this->graphQL($LOGIN_QUERY)->assertExactJson(['data' => ['me' => null]]);

    $res = login();

    $this->graphQL($LOGIN_QUERY)->assertExactJson(['data' => ['me' => $res['data']['login']]]);
});
