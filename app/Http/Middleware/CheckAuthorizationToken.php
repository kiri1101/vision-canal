<?php

namespace App\Http\Middleware;

use App\Models\AuthorizationToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthorizationToken
{
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
        if ($token->count() === 0) {
            return response()->json([
                'message' => Lang::get('messages.error.server_error.inactive_authorization', [], 'en'),
                'code' => 500,
                'data' => []
            ]);
        }

        return $next($request->merge(['deviceToken' => $token]));
    }
}
