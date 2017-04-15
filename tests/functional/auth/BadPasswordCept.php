<?php
/**
 * @group auth
 */
$I = new FunctionalTester($scenario);
$I->wantTo('try to log in with a bad password');

$password = 'password';
$user = factory(App\Model\User::class)->create(['password' => password_hash($password, PASSWORD_DEFAULT)]);
$I->amNotLoggedIn();
$I->amOnRoute('login');
$I->submitForm('#form-login', [
    'email' => $user->email,
    'password' => 'bad-password'
]);
$I->seeIAmNotLoggedIn();
