<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isPasswordNeedChange
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check())
        {
            $user = $request->user();

            $allowedRoute = [
                'password.change',
                'password.change.post',
                'logout_post',
            ];

            if ($user->isMustChangePassword() && ! $request->routeIs(... $allowedRoute))
            {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
