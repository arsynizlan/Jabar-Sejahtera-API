<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Resources\ApiResponse;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role == 2) {
            return $next($request);
        }
        return response()->json(new ApiResponse(false, 'Anda tidak memiliki hak akses'), 403);
    }
}