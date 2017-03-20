<?php
use Framework\Middleware\CsrfInit;
use Framework\Middleware\CsrfValidate;
use Psr7Middlewares\Middleware\TrailingSlash;

/** @var \Slim\App $app */

//////////////////////////////////////////////
// Middleware
//////////////////////////////////////////////

//$auth = $app->getContainer()->get('App\Middleware\Authorization\Authorized');
//$guest = $app->getContainer()->get('App\Middleware\Authorization\NotAuthorized');

$app->add($app->getContainer()->get('Framework\Middleware\RepopulateForm'));
$app->add(new CsrfInit($app->getContainer()->get('csrf')));
$app->add($app->getContainer()->get('Framework\Middleware\StartSession'));
$app->add((new TrailingSlash(false))->redirect(301));

/** @var Framework\Csrf\CsrfManager $csrf */
$csrf = new CsrfValidate($app->getContainer()->get('csrf'), $app->getContainer()->get('session'));
/** @var \Framework\Validation\ValidateRequestFactory $validator */
$validator = $app->getContainer()->get('Framework\Validation\ValidateRequestFactory');
/** @var \Framework\Middleware\Authorization\HasAccessMiddlewareFactory $validator */
//$access = $app->getContainer()->get('App\Middleware\Authorization\HasAccessMiddlewareFactory');

//////////////////////////////////////////////
// Routes
//////////////////////////////////////////////

// Home Page
$app->get('/', function($request, $response){

})->setName('home');
