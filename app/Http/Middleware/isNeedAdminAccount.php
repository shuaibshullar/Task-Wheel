<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isNeedAdminAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $goToRegisterPage    = ! auth()->check()
                            && ! User::isAnyAdminSet()
                            && ! $request->routeIs('register_get', 'register_post');


        if ($goToRegisterPage)
            return redirect()->route('register_get');


        return $next($request);
    }
}
