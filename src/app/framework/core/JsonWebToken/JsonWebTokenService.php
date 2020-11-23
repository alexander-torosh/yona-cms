<?php

namespace Core\JsonWebToken;

use DateTime;
use Firebase\JWT\JWT;

class JsonWebTokenService
{
    // @TODO Replace it by auto-generated cached value
    const KEY = 'iWySEH6cPdsBauYISE76FR5fQBEHCESCi1nzi4peddsAzLm6Dakg3DnvnckrnhH';

    public static function createToken(array $data = [], $expiresIn = '1 day'): string
    {
        $expirationDate = new DateTime();
        $expirationDate->modify('+'.$expiresIn);

        $exp = $expirationDate->format('U');

        // Validate exp value. If less than now:
        if ($exp < time()) {
            // Set default exp value.
            $exp = time() + 3600 * 24; // 1 day
        }

        $payload = [
            'iss' => 'YonaCMS',
            'exp' => $exp,
        ];
        $payload = $payload + $data;

        return JWT::encode($payload, self::KEY);
    }

    public static function decodeToken($token)
    {
        return JWT::decode($token, self::KEY, ['HS256']);
    }
}
