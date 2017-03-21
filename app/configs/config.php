<?php
return [

    /*
    |--------------------------------------------------------------------------
    | General Application settings
    |--------------------------------------------------------------------------
    */

    'app' => [
        'url'              => env('APP_URL'),
        // Timezone used internally for storing and calculating dates
        // Recommended to leave as UTC
        'timezone'         => env('TIMEZONE', 'UTC'),
        // Default timezone used when displaying dates.
        // Change to timezone of most users
        'display_timezone' => env('TIMEZONE_DISPLAY', 'UTC'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Settings
    |--------------------------------------------------------------------------
    */

    'database' => [
        'default' => [
            'driver'    => env('DATABASE_DRIVER', 'mysql'),
            'host'      => env('DATABASE_HOST'),
            'dbname'    => env('DATABASE_DBNAME'),
            'username'  => env('DATABASE_USER'),
            'password'  => env('DATABASE_PASSWORD'),
            'charset'   => env('DATABASE_CHARSET', 'utf8mb4'),
            'collation' => env('DATABASE_COLLATION', 'utf8mb4_unicode_520_ci'),
            'prefix'    => env('DATABASE_PREFIX', ''),
            'strict'    => env('DATABASE_STRICT', true),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache settings
    |--------------------------------------------------------------------------
    */

    'cache' => [
        // Can be array, file, apc, redis, memcache, memcached, or ioc container key that resolves to driver
        // Set to array to disable cache
        'driver' => env('CACHE_DRIVER', 'file'),
        // Cache prefix is useful for when multiple applications are sharing the cache
        'prefix' => env('CACHE_PREFIX', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mail settings
    |--------------------------------------------------------------------------
    */

    'mail' => [
        'from'        => [
            'address' => env('MAIL_FROM_ADDRESS'),
            'name'    => env('MAIL_FROM_NAME'),
        ],
        // Can be mail, sendmail, or smtp
        'driver'      => env('MAIL_DRIVER', 'mail'),
        // Options passed to the mail() function when using the mail driver
        'mailoptions' => env('MAIL_OPTIONS', '-f%s'),
        // The location of the sendmail binary for the sendmail driver
        'sendmail'    => env('MAIL_SENDMAIL', '/usr/sbin/sendmail -bs'),
        // smtp credentials
        'smtp'        => [
            'server'   => env('MAIL_SMTP_SERVER', 'smtp.example.org'),
            'port'     => env('MAIL_SMTP_PORT', 587),
            'encrypt'  => env('MAIL_SMTP_ENCRYPT', 'tls'),
            'user'     => env('MAIL_SMTP_USER'),
            'password' => env('MAIL_SMTP_PASSWORD'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Filesystem settings
    |--------------------------------------------------------------------------
    */

    'filesystem' => [
        'default' => env('FILESYSTEM_DEFAULT', 'local'),
        'disks'   => [
            'local'  => [
                'driver' => 'local',
                'root'   => storage_path('app'),
                'visibility' => 'private',
            ],
            'public' => [
                'driver' => 'local',
                'root'   => storage_path('public'),
                'visibility' => 'public',
            ],

//            's3' => [
//                'driver' => 's3',
//                'bucket' => env('AWS_S3_BUCKET'),
//                'prefix' => env('AWS_S3_PREFIX', ''),
//                'visibility' => 'private',
//            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption settings
    |--------------------------------------------------------------------------
    */

    'crypt' => [
        'key'  => env('CRYPT_KEY'),
        'algo' => env('CRYPT_ALGO', 'aes'),
        'mode' => env('CRYPT_MODE', 'cbc'),
        'hash' => env('CRYPT_HASH', 'sha256'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue settings
    |--------------------------------------------------------------------------
    */

    'queue' => [
        'enabled'    => env('QUEUE_ENABLED', false),
        'name'       => env('QUEUE_NAME'),
        // The queue driver to use, must be beanstalkd, iron, or sqs
        'driver'     => env('QUEUE_DRIVER', 'beanstalkd'),
        'beanstalkd' => [
            'host' => env('QUEUE_BEANSTALKD_SERVER', '127.0.0.1'),
            'port' => env('QUEUE_BEANSTALKD_PORT', 11300),
        ],
        'iron'       => [
            'token'   => env('QUEUE_IRON_TOKEN'),
            'project' => env('QUEUE_IRON_PROJECT'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis settings
    |--------------------------------------------------------------------------
    */

    'redis' => [
        // Can be redis for extension or predis for predis/predis
        'driver' => env('REDIS_DRIVER', 'predis'),
        // tcp or unix for unix socket
        'scheme' => env('REDIS_SCHEME', 'tcp'),
        // The hostname for tcp or socket file for unix socket
        'host'   => env('REDIS_HOST', '127.0.0.1'),
        // Redis port, not used for unix socket
        'port'   => env('REDIS_PORT', 6379),
    ],

    /*
    |--------------------------------------------------------------------------
    | Memcache settings
    |--------------------------------------------------------------------------
    */

    'memcache' => [
        // Can be memcache or memcached
        'driver'  => env('MEMCACHE_DRIVER', 'memcached'),
        'host'    => env('MEMCACHE_HOST', '127.0.0.1'),
        'port'    => env('MEMCACHE_PORT', 11211),
        'timeout' => env('MEMCACHE_TIMEOUT'),
    ],

    /*
    |--------------------------------------------------------------------------
    | AWS settings
    |--------------------------------------------------------------------------
    */

    'aws' => [

        'key'    => env('AWS_ACCESS_KEY'),
        'secret' => env('AWS_SECRET_KEY'),
        'region' => env('AWS_REGION', 'us-east-1'),

        's3' => [
            'bucket' => env('AWS_S3_BUCKET'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Facebook App settings
    |--------------------------------------------------------------------------
    */

    'facebook' => [
        'id'     => env('FACEBOOK_APP_ID'),
        'secret' => env('FACEBOOK_APP_SECRET'),
    ],
];
