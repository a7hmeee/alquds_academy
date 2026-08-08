<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;
    /**
     * Get the correct dashboard route based on the user's role.
     */
    protected function dashboardRoute(): string
    {
        $user = request()->user();
        if ($user && $user->hasRole('student')) {
            return route('student.dashboard', absolute: false);
        }
        return route('admin.dashboard', absolute: false);
    }
}
