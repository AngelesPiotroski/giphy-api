<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthenticationService;
use App\Services\ErrorLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthenticationController extends Controller
{
    protected AuthenticationService $authService;
    protected ErrorLogger $logger;

    public function __construct(AuthenticationService $authService, ErrorLogger $logger)
    {
        $this->authService = $authService;
        $this->logger      = $logger;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $tokenResult = $this->authService->authenticate($request->email, $request->password);

            if (! $tokenResult) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials.',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'token'   => $tokenResult->accessToken,
                'expires' => $tokenResult->token->expires_at,
                'user'    => $tokenResult->token->user,
            ]);
        } catch (Throwable $e) {
            $this->logger->log('AuthenticationController@store', $e, [
                'email' => $request->input('email'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al autenticar.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy()
    {
        try {
            $this->authService->logout();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out.',
            ]);
        } catch (Throwable $e) {
            $this->logger->log('AuthenticationController@destroy', $e);

            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}