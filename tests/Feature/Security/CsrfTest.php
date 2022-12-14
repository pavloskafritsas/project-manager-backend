<?php

use function Pest\Laravel\get;

test('can generate CSRF token', function () {
    get('/sanctum/csrf-cookie')
        ->assertStatus(204)
        ->assertCookie('XSRF-TOKEN', csrf_token());
});
