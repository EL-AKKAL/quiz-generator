<?php

use App\Models\User;

/**
 * Get the currently authenticated user.
 *
 * @return User|null
 */
function user(): ?User
{
    /** @var User|null $user */
    $user = auth()->user();
    return $user;
}
