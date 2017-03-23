<?php
require_once(__DIR__ . '/framework/bootstrap.php');

/** @var Illuminate\Database\Capsule\Manager $db */
$db = $container->get('Illuminate\Database\Capsule\Manager');
/** @var PDO $pdo */
$pdo = $db->getConnection()->getPdo();

return [
    'paths'        => [
        "migrations" => "%%PHINX_CONFIG_DIR%%/database/migrations",
        "seeds"      => "%%PHINX_CONFIG_DIR%%/database/seeds",
    ],
    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database"        => 'production',
        "production"              => [
            "name"         => env('DATABASE_DBNAME'),
            "connection"   => $pdo,
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
