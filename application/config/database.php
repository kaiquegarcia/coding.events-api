<?php

return [
    'fetch' => PDO::FETCH_CLASS,
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => [
        'testing' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', ':memory:'),
            'prefix' => env('DB_PREFIX', ''),
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'docker_db'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix' => env('DB_PREFIX', ''),
            'timezone' => env('APP_TIMEZONE', 'UTC'),
            'strict' => env('DB_STRICT_MODE', false),
        ],
    ],
    'migrations' => 'migrations',
];
