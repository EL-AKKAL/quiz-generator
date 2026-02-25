<?php

use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Features;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;

describe('Authentication', function () {

    it('shows the login screen', function () {
        get(route('login'))->assertOk();
    });

    it('authenticates a user with valid credentials', function () {

        loginAs(createUser())->assertRedirect(route('dashboard', absolute: false));

        assertAuthenticated();
    });

    it('redirects to two factor challenge if enabled', function () {

        if (! Features::canManageTwoFactorAuthentication()) {
            test()->markTestSkipped('Two-factor authentication is not enabled.');
        }

        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]);

        $user = createUser(withTwoFactor: true);

        loginAs($user)
            ->assertRedirect(route('two-factor.login'))
            ->assertSessionHas('login.id', $user->id);

        assertGuest();
    });

    it('prevents login with invalid password', function () {
        $user = createUser();

        loginAs($user, ['password' => 'wrong-password'])->assertSessionHasErrors();

        assertGuest();
    });

    it('logs out successfully', function () {
        actingAs(createUser())
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        assertGuest();
    });

    it('prevents too many login attempts', function () {
        $user = createUser();

        RateLimiter::increment(md5('login'.implode('|', [$user->email, '127.0.0.1'])), amount: 5);

        loginAs($user, ['password' => 'wrong-password'])->assertTooManyRequests();
    });

});
