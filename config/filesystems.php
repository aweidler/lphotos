<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('photos/private'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('photos/public'),
            'visibility' => 'public',
        ],

        'raw' => [
            'driver' => env('AZURE_DRIVER'),
            'root' => storage_path('photos/raw'),
            'endpoint' => env('AZURE_BLOB_STORAGE_ENDPOINT'),
            'container' => env('AZURE_BLOB_STORAGE_CONTAINER1'),
            'blob_service_url' => env('AZURE_BLOB_SERVICE_URL'),
            'visibility' => 'public',
        ],

        'rawedits' => [
            'driver' => env('AZURE_DRIVER'),
            'root' => storage_path('photos/rawedits'),
            'endpoint' => env('AZURE_BLOB_STORAGE_ENDPOINT'),
            'container' => env('AZURE_BLOB_STORAGE_CONTAINER1'),
            'blob_service_url' => env('AZURE_BLOB_SERVICE_URL'),
            'visibility' => 'public',
        ],

        'small' => [
            'driver' => env('AZURE_DRIVER'),
            'root' => storage_path('photos/small'),
            'endpoint' => env('AZURE_BLOB_STORAGE_ENDPOINT'),
            'container' => env('AZURE_BLOB_STORAGE_CONTAINER1'),
            'blob_service_url' => env('AZURE_BLOB_SERVICE_URL'),
            'visibility' => 'public',
        ],

        'medium' => [
            'driver' => env('AZURE_DRIVER'),
            'root' => storage_path('photos/medium'),
            'endpoint' => env('AZURE_BLOB_STORAGE_ENDPOINT'),
            'container' => env('AZURE_BLOB_STORAGE_CONTAINER1'),
            'blob_service_url' => env('AZURE_BLOB_SERVICE_URL'),
            'visibility' => 'public',
        ],

        'large' => [
            'driver' => env('AZURE_DRIVER'),
            'root' => storage_path('photos/large'),
            'endpoint' => env('AZURE_BLOB_STORAGE_ENDPOINT'),
            'container' => env('AZURE_BLOB_STORAGE_CONTAINER1'),
            'blob_service_url' => env('AZURE_BLOB_SERVICE_URL'),
            'visibility' => 'public',
        ],

        'full' => [
            'driver' => env('AZURE_DRIVER'),
            'root' => storage_path('photos/full'),
            'endpoint' => env('AZURE_BLOB_STORAGE_ENDPOINT'),
            'container' => env('AZURE_BLOB_STORAGE_CONTAINER1'),
            'blob_service_url' => env('AZURE_BLOB_SERVICE_URL'),
            'visibility' => 'public',
        ],

    ],

];
