<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        \Log::info('Request parameters:', $request->all());
        \Log::info('User authenticated:', auth()->check());
        \Log::info('User status:', auth()->user() ? auth()->user()->status : 'No user');
    
        if (auth()->check() && auth()->user()->status !== 'active') {
            if (\Route::is('auth.account-status')){
                return $next($request);
            }
    
            return redirect()->route('auth.account-status');
        }
    
        return $next($request);
    }
}
