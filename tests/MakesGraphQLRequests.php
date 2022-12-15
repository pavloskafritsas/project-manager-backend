<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests as LighthouseMakesGraphQLRequests;

trait MakesGraphQLRequests
{
    use LighthouseMakesGraphQLRequests {
        graphQL as lighthouseGraphQL;
    }

    /**
     * The default request headers.
     *
     * @return array<string, string>
     */
    protected function getDefaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Origin' => getenv('APP_URL'),
            'X-XSRF-TOKEN' => getCsrfToken(),
        ];
    }

    /**
     * Execute a GraphQL operation as if it was sent as a request to the server.
     *
     * @param  string  $query  The GraphQL operation to send
     * @param  array<string, mixed>  $variables  The variables to include in the query
     * @param  array<string, mixed>  $extraParams  Extra parameters to add to the JSON payload
     * @param  array<string, mixed>  $headers  HTTP headers to pass to the POST request
     * @return \Illuminate\Testing\TestResponse
     */
    protected function graphQL(
        string $query,
        array $variables = [],
        array $extraParams = [],
        array $headers = []
    ): TestResponse {
        $headers = $headers + $this->getDefaultHeaders();

        return $this->lighthouseGraphQL(
            $query,
            $variables,
            $extraParams,
            $headers
        );
    }
}
