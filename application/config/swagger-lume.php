<?php

return [
    'api' => [
        'title' => env('APP_NAME'),
    ],
    'routes' => [
        'api' => env('SWAGGER_PATH', '/'),
        'docs' => '/docs',
        'oauth2_callback' => '/api/oauth2-callback',
        'assets' => '/swagger-ui-assets',
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
    ],
    'paths' => [
        'docs' => base_path(''),
        'docs_json' => 'openapi.yaml',
        'annotations' => base_path('app'),
        'excludes' => [],
        'base' => env('L5_SWAGGER_BASE_PATH'),
        'views' => base_path('resources/views/vendor/swagger-lume'),
    ],
    'security' => [],
    'generate_always' => env('SWAGGER_GENERATE_ALWAYS', false),
    'swagger_version' => env('SWAGGER_VERSION', '3.0'),
    'proxy' => false,
    'additional_config_url' => null,
    'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT'),
    'validator_url' => null,
    'constants' => [],
];
