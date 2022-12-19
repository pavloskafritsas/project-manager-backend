<?php

namespace Tests\Feature\Models\Project;

enum GraphQLHelper
{
    case MUTATION_CREATE_PROJECT;
    case MUTATION_UPDATE_PROJECT;
    case MUTATION_DELETE_PROJECT;
    case QUERY_PROJECTS;

    public function getTypename(): array
    {
        return ['__typename' => 'Project'];
    }

    public function generateResponse(?array $response = null, bool $mergeTypename = true): array
    {
        if (is_array($response) && $mergeTypename) {
            $response += $this->getTypeName();
        }

        return match ($this) {
            self::MUTATION_CREATE_PROJECT => ['data' => ['createProject' => $response]],
            self::MUTATION_UPDATE_PROJECT => ['data' => ['updateProject' => $response]],
            self::MUTATION_DELETE_PROJECT => ['data' => ['deleteProject' => $response]],
            self::QUERY_PROJECTS => ['data' => ['projects' => $response]],
        };
    }

    public function operation(): string
    {
        return match ($this) {
            self::MUTATION_CREATE_PROJECT =>
            /** @lang GraphQL */
            '
            mutation($input: CreateProjectInput!)
            {
                createProject(input: $input) {
                    __typename
                    name
                    metas {
                        __typename
                        id
                        attribute
                        value
                        type
                    }
                    tasks {
                        __typename
                        id
                        name
                        description
                        priority
                        status
                        from
                        to
                    }
                }
            }
            ',
            self::MUTATION_DELETE_PROJECT =>
            /** @lang GraphQL */
            '
            mutation($id: ID!)
            {
                deleteProject(id: $id) {
                    __typename
                    name
                }
            }
            ',
            self::MUTATION_UPDATE_PROJECT =>
            /** @lang GraphQL */
            '
            mutation($id: ID!, $input: UpdateProjectInput!)
            {
                updateProject(id: $id, input: $input) {
                    __typename
                    name
                    metas {
                        __typename
                        id
                        attribute
                        value
                        type
                    }
                    tasks {
                        __typename
                        id
                        name
                        description
                        priority
                        status
                        from
                        to
                    }
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
                        __typename
                        id
                        name
                        metas {
                            __typename
                            id
                            attribute
                            value
                            type
                        }
                        tasks {
                            __typename
                            id
                            name
                            description
                            priority
                            status
                        }
                    }
                }
            }
            '
        };
    }
}
