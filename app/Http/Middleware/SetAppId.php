<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAppId
{

public function handle(Request $request, Closure $next)
{
    if ($request->has('app_id')) {
        app()->instance('current_app_id', (int) $request->query('app_id'));
    }

    return $next($request);
}


}
