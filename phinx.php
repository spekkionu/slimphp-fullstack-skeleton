<?php

require_once(__DIR__ . '/framework/bootstrap.php');

return [
    'paths'        => [
        "migrations" => "%%PHINX_CONFIG_DIR%%/database/migrations",
        "seeds"      => "%%PHINX_CONFIG_DIR%%/database/seeds",
    ],
    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database"        => 'production',
        "production"              => [
            "adapter"      => env('DATABASE_DRIVER', "mysql"),
            "host"         => env('DATABASE_HOST'),
            "name"         => env('DATABASE_DBNAME'),
            "user"         => env('DATABASE_USER'),
            "pass"         => env('DATABASE_PASSWORD'),
            "port"         => env('DATABASE_PORT', 3306),
            "charset"      => env('DATABASE_CHARSET', "utf8mb4"),
            "table_prefix" => env('DATABASE_PREFIX', ''),
        ],
        "testing"                 => [
            "adapter" => "mysql",
            "host"    => 'localhost',
            "name"    => 'testing',
            "user"    => 'tester',
            "pass"    => 'testpassword',
            "port"    => 3306,
            "charset" => "utf8",
        ],
    ],

];
