<?php 

namespace App\Core\Jwt;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler
{
    private static $secretKey = JWT_TOKEN; 
    private static $algorithm = 'HS256';

    public static function generateToken($payload)
    {
        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }

    public static function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

}