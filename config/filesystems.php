<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // Disk khusus dokumen Madocs. Disimpan TIDAK public — akses hanya
        // melalui route terkontrol (DocumentController@download) agar
        // permission benar-benar diverifikasi di backend, bukan hanya
        // mengandalkan URL publik yang bisa ditebak.
        'documents' => [
            'driver' => 'local',
            'root' => storage_path('app/private/documents'),
            'throw' => false,
        ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
