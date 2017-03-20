<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Set Application Paths
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}
if (!defined('APP_DIR')) {
    define('APP_DIR', APP_ROOT . DIRECTORY_SEPARATOR . 'app');
}
if (!defined('STORAGE_DIR')) {
    define('STORAGE_DIR', APP_ROOT . DIRECTORY_SEPARATOR . 'storage');
}
if (!defined('WEBROOT')) {
    define('WEBROOT', APP_ROOT . DIRECTORY_SEPARATOR . 'public');
}

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = new Dotenv\Dotenv(APP_ROOT);
if (file_exists(APP_ROOT . '/.env')) {
    $dotenv->load();
}

// Init Service Container
$container = require APP_DIR . '/configs/services.php';
