<?php

return [

    'default' => env('NOTIFICATION_DRIVER', 'mail'),

    'drivers' => [
        'mail' => [
            'driver' => 'mail',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'notifications',
        ],

        'fcm' => [
            'driver' => 'fcm',
        ],
    ],

    'fcm' => [
        'server_key' => env('FCM_SERVER_KEY'),
        'sender_id' => env('FCM_SENDER_ID'),
        'project_id' => env('FCM_PROJECT_ID'),
        'database_url' => env('FCM_DATABASE_URL'),
        'storage_bucket' => env('FCM_STORAGE_BUCKET'),
    ],

];
