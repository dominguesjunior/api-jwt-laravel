<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class DatabaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        DB::beginTransaction();
        $response = $next($request);
        if ($response instanceof BaseResponse && ($response->isSuccessful() || $response->isRedirection())) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $response;
    }
}
