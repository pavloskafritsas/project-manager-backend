<?php

test('can return the currently authenticated user', function () {
    $ME_QUERY =
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

    $this->graphQL($ME_QUERY)->assertExactJson(['data' => ['me' => null]]);

    $res = login();

    $this->graphQL($ME_QUERY)->assertExactJson(['data' => ['me' => $res['data']['login']]]);
});
