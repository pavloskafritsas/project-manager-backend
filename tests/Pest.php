<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\Feature\Auth\AuthOperation;

uses(\Tests\TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function getCsrfToken(): string
{
    /** @var \Illuminate\Testing\TestResponse $res */
    $res = test()->get('/sanctum/csrf-cookie');

    return $res->getCookie('XSRF-TOKEN')->getValue();
}

function login(?User $user = null, ?bool $remember = null): TestResponse
{
    if (! $user) {
        /** @var User $user */
        $user = User::factory()->createOne();
    }

    $data = [
        'email' => $user->email,
        'password' => 'password',
    ] + (isset($remember) ? compact('remember') : []);

    return test()->graphQL(AuthOperation::MUTATION_LOGIN, $data);
}

function logout(?User $user = null): TestResponse
{
    if (! $user) {
        /** @var User $user */
        $user = User::factory()->createOne();
    }

    return test()->graphQL(AuthOperation::MUTATION_LOGOUT);
}
