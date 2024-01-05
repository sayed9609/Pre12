<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token');
        $result = JWTToken::VerifyToken($token);

        if ($result == "unauthorized") {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized User'
            ],401);
        } else {
            $request->headers->set('email', $result);
            return $next($request);
        }
    }
}
