<?php

namespace App\Helpers;

class JwtHelper
{
    public static function encode(array $payload): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];

        $segments = [
            self::base64UrlEncode(json_encode($header)),
            self::base64UrlEncode(json_encode($payload)),
        ];

        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, env('JWT_SECRET'), true);

        $segments[] = self::base64UrlEncode($signature);

        return implode('.', $segments);
    }

    public static function decode(string $jwt)
    {
        $segments = explode('.', $jwt);

        if (count($segments) !== 3) {
            return null;
        }

        list($header, $payload, $signature) = $segments;

        $validSignature = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", env('JWT_SECRET'), true)
        );

        if (!hash_equals($validSignature, $signature)) {
            return null;
        }

        return json_decode(self::base64UrlDecode($payload), true);
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
