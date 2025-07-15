<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreSsoToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('token')) {
            session(['sso_token' => $request->query('token')]);
        }

        \Log::info('SSO Token:' , [session('sso_token')]);

        return $next($request);
    }
}
