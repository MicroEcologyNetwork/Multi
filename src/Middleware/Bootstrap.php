<?php

namespace MicroEcology\Multi\Middleware;

use Closure;
use MicroEcology\Multi\Facades\Multi;
use Illuminate\Http\Request;

class Bootstrap
{
    public function handle(Request $request, Closure $next)
    {
        Multi::bootstrap();

        return $next($request);
    }
}
