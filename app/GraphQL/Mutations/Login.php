<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Error\Error;
use Nuwave\Lighthouse\Schema\Context;

final class Login
{
    /**
     * @param  null  $_
     * @param  array{email: string, password: string, remember?: bool}  $args
     * @param  Context  $ctx
     * @return User
     */
    public function __invoke(null $_, array $args, Context $ctx): User
    {
        $guard = auth()->guard();

        $credentials = [
            'email' => $args['email'],
            'password' => $args['password'],
        ];

        $remember = $args['remember'] ?? false;
        assert(is_bool($remember));

        if (! $guard->attempt($credentials, $remember)) {
            $message = __('auth.failed');
            assert(is_string($message));

            throw new Error($message);
        }

        $ctx->request->session()->regenerate();

        $user = $guard->user();
        assert($user instanceof User);

        return $user;
    }
}
