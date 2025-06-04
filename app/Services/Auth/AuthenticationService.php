<?php
namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthenticationService
{

    public function authenticate(string $email, string $password): ?PersonalAccessTokenResult
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        // Expiración del token: 30 minutos
        $tokenResult       = $user->createToken('appToken');
        $token             = $tokenResult->token;
        $token->expires_at = now()->addMinutes(30);
        $token->save();

        return $tokenResult;
    }

    public function logout(): JsonResponse
    {
        $user = request()->user();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'No hay sesión activa.',
            ], 401);
        }
        if ($user && $user->token()) {
            $user->token()->revoke();
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out.',
        ]);

    }
}