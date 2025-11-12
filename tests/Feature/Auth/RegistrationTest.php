<?php

use function Pest\Laravel\{get, post, assertAuthenticated};

describe("Registration", function () {

    it('shows the register screen', function () {
        get(route('register'))->assertOk();
    });

    it('registers a new user successfully', function () {

        post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        assertAuthenticated();
    });
});
