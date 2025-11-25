<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('jobstartboerse.api.key');

        abort_if(blank($apiKey), Response::HTTP_INTERNAL_SERVER_ERROR, 'No API key configured');

        $token = $request->bearerToken();

        abort_if($token !== $apiKey, Response::HTTP_UNAUTHORIZED);

        return $next($request);
    }
}
