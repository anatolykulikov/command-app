<?php

namespace App\Helpers;

use App\Actions\Auth\DTO\CreateNewToken;
use App\Actions\Auth\DTO\CreateToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UserHelper
{
    const CookieName = 'applud';

    public static function setAuthCookie(CreateNewToken $token): void
    {
        $value = base64_encode(implode(':', [
            'key' => $token->getKey(),
            'expires' => $token->getExpired(),
        ]));

        setcookie(
            self::CookieName,
            $value,
            [
                'path' => '/',
                'expires' => $token->getExpired(),
                'httponly' => true,
                'samesite' => 'strict'
            ]
        );
    }

    public static function readAuthCookie(Request $request): ?CreateToken
    {
        if(!$request->hasCookie(UserHelper::CookieName)) return null;
        $cookie = explode(':', base64_decode($request->cookie(UserHelper::CookieName)));
        if(!$cookie) return null;
        return new CreateToken($cookie[0], $cookie[1], $cookie[2] ?? null);
    }

    public static function deleteAuthCookie(): void
    {
        setcookie(
            self::CookieName,
            '',
            [
                'path' => '/',
                'expires' => time()-3600,
                'httponly' => true,
                'samesite' => 'strict'
            ]
        );

    }

    public static function hashPassword(string $pass): string
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    public static function generateRandomPassword(int $length = 64): string
    {
        try {
            return self::hashPassword(bin2hex(random_bytes($length)));
        } catch (\Exception $e) {
            return self::hashPassword(bin2hex(mt_rand(100000, 9999999)));
        }
    }

    public static function comparePasswords(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

}
