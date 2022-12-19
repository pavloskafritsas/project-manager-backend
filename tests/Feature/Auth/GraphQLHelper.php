<?php

namespace Tests\Feature\Auth;

enum GraphQLHelper
{
    case MUTATION_LOGIN;
    case MUTATION_LOGOUT;
    case QUERY_ME;

    public function getTypename(): array
    {
        return ['__typename' => 'User'];
    }

    public function generateResponse(?array $response = null, bool $mergeTypename = true): array
    {
        if ($response && $mergeTypename) {
            $response += $this->getTypeName();
        }

        return match ($this) {
            self::MUTATION_LOGIN => ['data' => ['login' => $response]],
            self::MUTATION_LOGOUT => ['data' => ['logout' => $response]],
            self::QUERY_ME => ['data' => ['me' => $response]],
        };
    }

    public function operation(): string
    {
        return match ($this) {
            self::MUTATION_LOGIN =>
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
            ',
            self::MUTATION_LOGOUT =>
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
            ',
            self::QUERY_ME =>
            /** @lang GraphQL */
            '
            query
            {
                me {
                    __typename
                    id
                    name
                    email
                }
            }
            '
        };
    }
}
