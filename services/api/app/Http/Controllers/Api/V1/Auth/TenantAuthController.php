<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class TenantAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::with('tenant')
            ->where('email', $credentials['email'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password tidak valid.',
            ], 401);
        }

        if (
            $user->status !== 'active' ||
            !$user->tenant ||
            $user->tenant->status !== 'active'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Akun atau tenant tidak aktif.',
            ], 403);
        }

        $token = $user->createToken('blesspos-api')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $this->userData($user),
            ],
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user()->load('tenant');

        return response()->json([
            'success' => true,
            'data' => $this->userData($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    private function userData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'account_type' => $user->account_type,
            'phone' => $user->phone,
            'status' => $user->status,
            'tenant' => $user->tenant ? [
                'id' => $user->tenant->id,
                'name' => $user->tenant->name,
                'slug' => $user->tenant->slug,
                'business_type' => $user->tenant->business_type,
                'status' => $user->tenant->status,
            ] : null,
        ];
    }
}