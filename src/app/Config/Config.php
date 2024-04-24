<?php

namespace App\Config;

const connections = [];

class Config
{

    private static function connections()
    {
        return [
            'mysql' => [
                'driver' => 'mysql',
                'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
                'port' => $_ENV['DB_PORT'] ?? 3306,
                'database' => $_ENV['DB_DATABASE'] ?? 'app',
                'username' => $_ENV['DB_USERNAME'] ?? 'app',
                'password' => $_ENV['DB_PASSWORD'] ?? 'app',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            ],

        ];
    }


    public static function getDatabaseConfig($name = 'mysql')
    {

        if (!isset(self::connections()[$name])) {
            throw new \Exception('Database config not found');
        }

        return self::connections()[$name];
    }
}
