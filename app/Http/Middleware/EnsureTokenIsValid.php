<?php

namespace App\Http\Middleware;

use App\Http\Resources\AuthErrorResource;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        $user = User::firstWhere('api_token', $token);

        if (!$token || !$user) {
            return (new AuthErrorResource($request))->response()->setStatusCode(403);
        }

        auth()->login($user);
        return $next($request);
    }
}
