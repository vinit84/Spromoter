<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiStoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $exists = Store::whereUuid($request->header('X-App-Id'))->exists();
        if (!$exists) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        return $next($request);
    }
}
