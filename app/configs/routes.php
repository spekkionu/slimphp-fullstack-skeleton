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
// Registration
//////////////////////////////////////////////
$app->get('/register', 'App\Controller\Auth\RegistrationController:index')->setName('register');
$app->post('/register', 'App\Controller\Auth\RegistrationController:register')->add($validator('App\Form\Auth\RegistrationForm', ['password', 'password_confirm']))->add($csrf);

//////////////////////////////////////////////
// Login
//////////////////////////////////////////////
$app->get('/login', 'App\Controller\Auth\LoginController:index')->setName('login');
$app->post('/login', 'App\Controller\Auth\LoginController:login')->add($validator('App\Form\Auth\LoginForm', ['password']))->add($csrf);
$app->get('/logout', 'App\Controller\Auth\LoginController:logout')->setName('logout');

//////////////////////////////////////////////
// Password Reset
//////////////////////////////////////////////
$app->get('/login/password', 'App\Controller\Auth\PasswordController:index')->setName('login.password');
$app->post('/login/password', 'App\Controller\Auth\PasswordController:send')->add($validator('App\Form\Auth\ForgotPasswordForm'))->add($csrf);
$app->get('/login/password/submitted', 'App\Controller\Auth\PasswordController:submitted')->setName('login.password.submitted');
$app->get('/login/password/{token:[a-zA-Z0-9]{16}}', 'App\Controller\Auth\PasswordController:confirm')->setName('login.password.confirm');
$app->post('/login/password/{token:[a-zA-Z0-9]{16}}', 'App\Controller\Auth\PasswordController:reset')->add($csrf);

//////////////////////////////////////////////
// Account Management
//////////////////////////////////////////////
$app->group('/account', function () use ($csrf, $validator) {
    $this->get('/', 'App\Controller\Account\AccountController:dashboard')->setName('account');
    $this->get('/profile', 'App\Controller\Account\ProfileController:profile')->setName('account.profile');
    $this->post('/profile', 'App\Controller\Account\ProfileController:saveProfile')->add($csrf);
    $this->get('/password', 'App\Controller\Account\PasswordController:password')->setName('account.password');
    $this->post('/password', 'App\Controller\Account\PasswordController:savePassword')->add($csrf);
})->add($auth);

// Home Page
$app->get('/', function($request, $response){

    return view('hello');
})->setName('home');
