<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class indexMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getRequestUri();

        $a = $uri === '/index.php';
        $b = ! USE_INDEX_ROUTE     &&      $uri === '/index';

        if( $a || $b ) throw new NotFoundHttpException();

        return $next($request);
    }

}
