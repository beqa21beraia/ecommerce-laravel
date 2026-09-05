<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function requestCode(string $phone): void
    {
        $code = (string) random_int(100000, 999999);

        Cache::put($this->cacheKey($phone), $code, now()->addMinutes(5));

        $this->sendCode($phone, $code);
    }

    public function verifyCode(string $phone, string $code): array
    {
        $storedCode = Cache::get($this->cacheKey($phone));

        if ($storedCode === null || $storedCode !== $code) {
            throw new \InvalidArgumentException('Invalid or expired verification code.');
        }

        Cache::forget($this->cacheKey($phone));

        $user = User::firstOrCreate(
            ['phone' => $phone],
            ['phone_verified_at' => now()]
        );

        if (is_null($user->phone_verified_at)) {
            $user->update(['phone_verified_at' => now()]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    protected function sendCode(string $phone, string $code): void
    {
        Log::info("Verification code for {$phone}: {$code}");
    }

    protected function cacheKey(string $phone): string
    {
        return 'verify:' . $phone;
    }
}
