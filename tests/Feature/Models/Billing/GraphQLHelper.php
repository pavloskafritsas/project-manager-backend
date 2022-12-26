<?php

namespace Tests\Feature\Models\Billing;

enum GraphQLHelper
{
    case MUTATION_CREATE_PROJECT_BILLING;
    case MUTATION_CREATE_TASK_BILLING;
    case MUTATION_UPDATE_BILLING;
    case MUTATION_DELETE_BILLING;
    case QUERY_PROJECTS;

    public function getTypename(): array
    {
        return ['__typename' => 'Billing'];
    }

    public function generateResponse(?array $response = null, bool $mergeTypename = true): array
    {
        if (is_array($response) && $mergeTypename) {
            $response += $this->getTypeName();
        }

        return match ($this) {
            self::MUTATION_CREATE_PROJECT_BILLING => ['data' => ['createProjectBilling' => $response]],
            self::MUTATION_CREATE_TASK_BILLING => ['data' => ['createTaskBilling' => $response]],
            self::MUTATION_UPDATE_BILLING => ['data' => ['updateBilling' => $response]],
            self::MUTATION_DELETE_BILLING => ['data' => ['deleteBilling' => $response]],
            self::QUERY_PROJECTS => ['data' => ['projects' => $response]],
        };
    }

    public function operation(): string
    {
        return match ($this) {
            self::MUTATION_CREATE_PROJECT_BILLING =>
            /** @lang GraphQL */
            '
            mutation($billingable_id: ID!, $input: CreateBillingInput!)
            {
                createProjectBilling(billingable_id: $billingable_id, input: $input) {
                    __typename
                    billingable {
                         __typename
                        ... on Project {
                            name
                        }
                    }
                    type
                    price_amount
                }
            }
            ',
            self::MUTATION_CREATE_TASK_BILLING =>
            /** @lang GraphQL */
            '
            mutation($billingable_id: ID!, $input: CreateBillingInput!)
            {
                createTaskBilling(billingable_id: $billingable_id, input: $input) {
                    __typename
                    billingable {
                         __typename
                        ... on Task {
                            name
                            description
                            priority
                            status
                        }
                    }
                    type
                    price_amount
                }
            }
            ',
            self::MUTATION_DELETE_BILLING =>
            /** @lang GraphQL */
            '
            mutation($id: ID!)
            {
                deleteBilling(id: $id) {
                    __typename
                     billingable {
                         __typename
                        ... on Project {
                            name
                        }
                        ... on Task {
                            name
                            description
                            priority
                            status
                        }
                    }
                    type
                    price_amount
                }
            }
            ',
            self::MUTATION_UPDATE_BILLING =>
            /** @lang GraphQL */
            '
            mutation($input: UpdateBillingInput!)
            {
                updateBilling(input: $input) {
                    __typename
                    billingable {
                         __typename
                        ... on Project {
                            name
                        }
                        ... on Task {
                            name
                            description
                            priority
                            status
                        }
                    }
                    type
                    price_amount
                }
            }
            ',
            self::QUERY_PROJECTS =>
            /** @lang GraphQL */
            '
            query
            {
                projects {
                    data {
                        billing {
                            __typename
                            type
                            price_amount
                        }
                        tasks {
                            billing {
                                __typename
                                type
                                price_amount
                            }
                        }
                    }
                }
            }
            '
        };
    }
}
