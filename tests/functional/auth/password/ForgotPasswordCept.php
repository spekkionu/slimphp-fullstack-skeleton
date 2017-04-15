<?php
/**
 * @group password
 */
$I = new FunctionalTester($scenario);
$I->wantTo('request a password reset');

$password = 'password';
$user = factory(App\Model\User::class)->create(['password' => password_hash($password, PASSWORD_DEFAULT)]);

$I->amOnRoute('login.password');
$I->submitForm('#form-forgot-password', [
    'email' => $user->email,
]);
$I->seeInDatabase('password_resets', ['email' => $user->email]);
