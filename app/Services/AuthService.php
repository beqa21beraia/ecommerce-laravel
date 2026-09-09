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

    public function verifyCode(string $phone, string $code, ?string $guestCartToken = null): array
    {
        $storedCode = Cache::get($this->cacheKey($phone));

        if ($storedCode === null || $storedCode !== $code) {
            throw new \InvalidArgumentException('Invalid or expired verification code.');
        }

        Cache::forget($this->cacheKey($phone));

        $user = User::where('phone', $phone)->first();
        $isNewUser = is_null($user);

        if ($isNewUser) {
            $user = User::create([
                'phone' => $phone,
                'phone_verified_at' => now(),
            ]);
        } elseif (is_null($user->phone_verified_at)) {
            $user->update(['phone_verified_at' => now()]);
        }

        if ($guestCartToken) {
            app(CartService::class)->mergeGuestCartIntoUserCart($guestCartToken, $user->id);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'is_new_user' => $isNewUser,
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
