<?php

return [
    'name' => env('APP_NAME', 'Madocs'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
    'locale' => env('APP_LOCALE', 'id'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'id_ID'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(explode(',', env('APP_PREVIOUS_KEYS', ''))),
    ],
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi khusus Madocs
    |--------------------------------------------------------------------------
    */
    'madocs' => [
        'app_name' => 'Madocs',
        'app_full_name' => 'Management Documents School',
        'max_upload_mb' => (int) env('MADOCS_MAX_UPLOAD_MB', 10),
        'allowed_ext' => explode(',', env('MADOCS_ALLOWED_EXT', 'pdf,doc,docx,xls,xlsx,ppt,pptx')),
    ],
];
