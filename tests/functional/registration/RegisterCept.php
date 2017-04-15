<?php
/**
 * @group registration
 */
$I = new FunctionalTester($scenario);
$I->wantTo('register a new account');

$password = 'password';
$user = factory(App\Model\User::class)->make(['password' => password_hash($password, PASSWORD_DEFAULT)]);

$I->amOnRoute('register');
$I->submitForm('#form-registration', [
    'name' => $user->name,
    'email' => $user->email,
    'password' => $password,
    'password_confirm' => $password,
]);
$I->seeInDatabase('users', ['email' => $user->email]);
