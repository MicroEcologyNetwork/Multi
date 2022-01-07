<?php

namespace Micro\Multi\Middleware;

use Closure;
use Micro\Multi\Facades\Multi;
use Illuminate\Http\Request;

class Bootstrap
{
    public function handle(Request $request, Closure $next)
    {
        Multi::bootstrap();

        return $next($request);
    }
}
