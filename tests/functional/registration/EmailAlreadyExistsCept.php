<?php
/**
 * @group registration
 */
$I = new FunctionalTester($scenario);
$I->wantTo('try to register with an email address that is already used');

$password = 'password';
$user = factory(App\Model\User::class)->create(['name' => 'Bob', 'password' => password_hash($password, PASSWORD_DEFAULT)]);

$I->amOnRoute('register');
$I->submitForm('#form-registration', [
    'name' => 'Steve',
    'email' => $user->email,
    'password' => $password,
    'password_confirm' => $password,
]);
$I->dontSeeInDatabase('users', ['email' => $user->email, 'name' => 'Steve']);
