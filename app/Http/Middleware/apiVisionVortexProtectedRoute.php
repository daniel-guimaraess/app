<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenEncoded;

class apiVisionVortexProtectedRoute
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token não fornecido'], 401);
        }

        try {
            $secretKey = env('VISIONVORTEX_SECRET_KEY');
            $tokenEncoded = new TokenEncoded($token);
            $jwt = new JWT();
            $decoded = $jwt->decode($tokenEncoded, $secretKey);
            $request->attributes->add(['jwt_payload' => $decoded->getPayload()]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token inválido.'], 401);
        }

        return $next($request);
    }
}
