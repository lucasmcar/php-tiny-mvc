<?php

namespace App\Support;

use Predis\Client;

class RedisClient
{
    private static ?Client $client = null;

    public static function get(): Client
    {
        if (self::$client === null) {
            self::$client = new Client([
                'scheme' => 'tcp',
                'host'   => getenv('REDIS_HOST') ?: '127.0.0.1',
                'port'   => getenv('REDIS_PORT') ?: 6379,
            ]);
        }

        return self::$client;
    }
}
