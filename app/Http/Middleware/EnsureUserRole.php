<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = User::firstWhere('api_token', $request->bearerToken());

        if ($user->group?->slug === $role) {
            return $next($request);
        }

        return response([
            'error' => [
                'code' => 403,
                'message' => 'Login failed',
            ],
        ], 403);
    }
}
