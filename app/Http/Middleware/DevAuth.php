<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class DevAuth
{
    public function handle($request, Closure $next)
    {
        if (app()->environment('local')) {
            auth()->login(User::first() ?? User::factory()->create());
        }

        return $next($request);
    }
}
