<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\JwtHelper;
use App\Models\User;

class AdminAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token missing'], 401);
        }

        $payload = JwtHelper::decode($token);

        if (!$payload || $payload['exp'] < time()) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $user = User::find($payload['sub']);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }
        if ($user->type != UserType::ADMIN) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Set authenticated user manually
        auth()->login($user);

        return $next($request);
    }
}
