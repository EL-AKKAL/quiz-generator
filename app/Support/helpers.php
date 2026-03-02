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

if (! function_exists('app_version')) {
    function app_version(): string
    {
        $package = json_decode(file_get_contents(base_path('package.json')), true);

        return $package['version'] ?? 'dev';
    }
}

if (! function_exists('app_version_last_updated')) {
    function app_version_last_updated(): ?string
    {
        $pkg = json_decode(file_get_contents(base_path('package.json')), true);

        return $pkg['lastUpdated'] ?? null;
    }
}
