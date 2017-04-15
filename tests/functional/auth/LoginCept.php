<?php
/**
 * @group auth
 */
$I = new FunctionalTester($scenario);
$I->wantTo('log in as user');

$password = 'password';
$user = factory(App\Model\User::class)->create(['password' => password_hash($password, PASSWORD_DEFAULT)]);

$I->amNotLoggedIn();
$I->amOnRoute('login');
$I->submitForm('#form-login', [
    'email' => $user->email,
    'password' => $password
]);
$I->seeIAmLoggedInAs($user->id);
