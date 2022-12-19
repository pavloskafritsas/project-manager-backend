<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Testing\TestResponse;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;
use Symfony\Component\HttpFoundation\Cookie;

test('can issue remember_web cookie on login per demand', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    $res = login($user, false);

    assertFalse(
        responseContainsRememberWebCookie($res),
        'Response contains remember_web cookie'
    );

    $res = login($user, true);

    assertTrue(
        responseContainsRememberWebCookie($res),
        'Response doesn\'t contain remember_web cookie'
    );
});

function responseContainsRememberWebCookie(TestResponse $res): bool
{
    $condition = false;

    collect($res->baseResponse->headers->getCookies())
        ->each(function (Cookie $cookie) use (&$condition) {
            if (str_starts_with($cookie->getName(), 'remember_web_')) {
                $condition = true;

                return false;
            }
        });

    return $condition;
}
