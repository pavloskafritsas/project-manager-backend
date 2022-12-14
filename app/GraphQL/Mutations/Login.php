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
     */
    public function __invoke(null $_, array $args, Context $ctx): User
    {
        $guard = auth()->guard();

        $credentials = [
            'email' => $args['email'],
            'password' => $args['password'],
        ];

        /** @var bool */
        $remember = $args['remember'] ?? false;

        if (! $guard->attempt($credentials, $remember)) {
            /** @var string $message */
            $message = __('auth.failed');

            throw new Error($message);
        }

        $ctx->request->session()->regenerate();

        /** @var User $user */
        $user = $guard->user();

        return $user;
    }
}
