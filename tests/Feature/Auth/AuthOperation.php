<?php

namespace Tests\Feature\Auth;

class AuthOperation
{
    public const MUTATION_LOGIN =
    /** @lang GraphQL */
    '
    mutation ($email: String!, $password: String!, $remember: Boolean)
        {
            login (email: $email, password: $password, remember: $remember) {
                __typename
                id
                name
                email
            }
        }
    ';

    public const MUTATION_LOGOUT =
    /** @lang GraphQL */
    '
    mutation
        {
            logout {
                __typename
                id
                name
                email
            }
        }
    ';

    public const QUERY_ME =
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
}
