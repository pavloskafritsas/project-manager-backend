<?php

namespace Tests;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests as LighthouseMakesGraphQLRequests;

trait MakesGraphQLRequests
{
    use LighthouseMakesGraphQLRequests;

    protected function getRequestHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Origin' => getenv('APP_URL'),
            'X-XSRF-TOKEN' => getCsrfToken(),
        ];
    }
}
