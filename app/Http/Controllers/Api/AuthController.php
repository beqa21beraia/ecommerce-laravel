<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function requestCode(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|regex:/^\+?[1-9]\d{7,14}$/',
        ]);

        $this->authService->requestCode($validated['phone']);

        return response()->json(['message' => 'Verification code sent.']);
    }

    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        try {
            $result = $this->authService->verifyCode($validated['phone'], $validated['code']);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'user' => $result['user'],
            'token' => $result['token'],
        ]);
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
