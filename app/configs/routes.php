<?php
use Psr7Middlewares\Middleware\TrailingSlash;

/** @var \Slim\App $app */

//////////////////////////////////////////////
// Middleware
//////////////////////////////////////////////

$app->add($app->getContainer()->get('Framework\Middleware\RepopulateForm'));
$app->add($app->getContainer()->get('Framework\Middleware\CsrfInit'));
$app->add($app->getContainer()->get('Framework\Middleware\StartSession'));
$app->add((new TrailingSlash(false))->redirect(301));

/** @var Framework\Middleware\Authorization\Authorized $auth */
$auth = $app->getContainer()->get('Framework\Middleware\Authorization\Authorized');
/** @var Framework\Middleware\Authorization\NotAuthorized $guest */
$guest = $app->getContainer()->get('Framework\Middleware\Authorization\NotAuthorized');
/** @var Framework\Middleware\CsrfValidate $csrf */
$csrf = $app->getContainer()->get('Framework\Middleware\CsrfValidate');
/** @var \Framework\Validation\ValidateRequestFactory $validator */
$validator = $app->getContainer()->get('Framework\Validation\ValidateRequestFactory');

//////////////////////////////////////////////
// Routes
//////////////////////////////////////////////

// Home Page
$app->get('/', function($request, $response){

    return $response->getBody()->write('Hello World');
})->setName('home');
