<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Traits\Helpers;
use Illuminate\Http\Request;
use App\Models\AuthorizationToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthorizationToken
{
    use Helpers;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if request has authorization token header
        if (!$request->hasHeader('Authorization-Token')) {
            return response()->json([
                'message' => Lang::get('messages.error.server_error.invalid_authorization', [], 'en'),
                'code' => 500,
                'data' => []
            ]);
        }

        $token = AuthorizationToken::isValid($request->header('Authorization-Token'))->isActive()->first();

        // Check if authorization token submitted is valid and active
        if (!empty($token)) {
            return $next($request->merge(['deviceToken' => $token]));
        } else {
            Log::info('device authentification failed', [
                'ip' => $request->ip()
            ]);

            return response()->json([
                'message' => Lang::get('messages.error.server_error.inactive_authorization', [], 'en'),
                'code' => 500,
                'data' => []
            ]);
        }
    }
}
