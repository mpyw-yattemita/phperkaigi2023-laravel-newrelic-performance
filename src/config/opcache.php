<?php

return [
    'url' => 'localhost:8002',
    'prefix' => 'opcache-api',
    'verify' => true,
    'headers' => [
        'Accept' => 'application/json',
    ],
    'directories' => [
        // 無期限キャッシュでアプリケーションコードを読み込む
        // （ゼロダウンタイムで破棄可能）
        base_path('app'),
        base_path('bootstrap'),
        base_path('config'),
        base_path('lang'),
        base_path('public'),
        base_path('routes'),
        base_path('storage/framework/views'),
    ],
    'preload_directories' => [
        // プリロードでライブラリコードを読み込む
        // （真の永続キャッシュ・破棄不能）
        base_path('vendor/composer'),
        base_path('vendor/laravel/framework'),
        base_path('vendor/monolog'),
        base_path('vendor/nesbot/carbon'),
        base_path('vendor/psr'),
        base_path('vendor/symfony'),
        base_path('vendor/vlucas/phpdotenv'),
    ],
    'exclude' => [
        'test',
        'Test',
        'tests',
        'Tests',
        'stub',
        'Stub',
        'stubs',
        'Stubs',
        'dumper',
        'Dumper',
        'Autoload',
    ],
];
