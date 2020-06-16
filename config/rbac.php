<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Model Class
    |--------------------------------------------------------------------------
    |
    | This value is the class of your user model.
    |
    */

    'user' => App\User::class,

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may specify a database connection and a common prefix
    | for your RBAC tables.
    |
    */

    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
        'prefix' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Switch
    |--------------------------------------------------------------------------
    |
    | This option may be used to disable the cache.
    |
    */

    'cache' => env('RBAC_CACHE', true),

];
