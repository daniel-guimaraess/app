<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }
        $user = User::where('email', $request->email)->first(); 

        return response()->json([
            'profile' => [
                'name' => $user->name,
            ],
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60         
        ]);
    }

    public function register(Request $request): JsonResponse {

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            return response()->json([
                'message' => 'User registered successfully'
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to register user',
                'error' => $th->getMessage()
            ], 400);
        }       
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Usuário deslogado com sucesso']);
    }
}
