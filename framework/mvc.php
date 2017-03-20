<?php

/** @var \Slim\App $app */
$app = $container->get('Slim\App');
if (!defined('MY_APP_STARTED')) {
    define('MY_APP_STARTED', true);
}
$app->run();
