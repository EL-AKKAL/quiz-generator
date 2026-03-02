<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Get the currently authenticated user.
 */
function user(): ?User
{
    return Auth::user();
}

if (!function_exists('appVersion')) {
    function appVersion(): string
    {
        $package = json_decode(file_get_contents(base_path('package.json')), true);

        return $package['version'] ?? 'dev';
    }
}

if (!function_exists('appVersionLastUpdated')) {
    function appVersionLastUpdated(): ?string
    {
        $pkg = json_decode(file_get_contents(base_path('package.json')), true);

        return $pkg['lastUpdated'] ?? null;
    }
}
