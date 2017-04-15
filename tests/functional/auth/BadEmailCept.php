<?php
/**
 * @group auth
 */
$I = new FunctionalTester($scenario);
$I->wantTo('try to login with an account that does not exist');


$password = 'password';
$user = factory(App\Model\User::class)->make(['password' => password_hash($password, PASSWORD_DEFAULT)]);
$I->dontSeeInDatabase('users', ['email' => $user->email]);
$I->amNotLoggedIn();
$I->amOnRoute('login');
$I->submitForm('#form-login', [
    'email' => $user->email,
    'password' => $password
]);
$I->seeIAmNotLoggedIn();
