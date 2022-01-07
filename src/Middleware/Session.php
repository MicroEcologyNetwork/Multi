<?php

namespace MicroEcology\Multi\Middleware;

use Illuminate\Http\Request;

class Session
{
    public function handle(Request $request, \Closure $next)
    {
        $path = '/'.trim(config('multi.route.prefix'), '/');

        config(['session.path' => $path]);

        if ($domain = config('multi.route.domain')) {
            config(['session.domain' => $domain]);
        }

        return $next($request);
    }
}
