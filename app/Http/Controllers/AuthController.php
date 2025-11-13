<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login auth
     * @param Request $request
     */
    public function login(Request $request) : JsonResponse
    {
        try {
            $validated = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'datos invalidos'
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !password_verify($request->password, $user->password)) return response()->json([
                'message' => 'Unauthorized'
            ], 401);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() 
            ]);
        }
    }

    public function verifyToken() : JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'token valido'
        ], 200);
    }
}
