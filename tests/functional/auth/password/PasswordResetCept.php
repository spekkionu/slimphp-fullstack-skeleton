<?php
/**
 * @group password
 */
use App\Model\User;

$I = new FunctionalTester($scenario);
$I->wantTo('reset my password');

/** @var \App\Model\User $user */
$user = factory(App\Model\User::class)->create(['password' => password_hash('oldpassword', PASSWORD_DEFAULT)]);

$token = mb_strtolower(str_random(16));
$I->haveInDatabase('password_resets', [
    'email'      => $user->email,
    'token'      => password_hash($token, PASSWORD_DEFAULT),
    'expires_at' => \Carbon\Carbon::parse('+1 hour'),
]);

$I->amOnRoute('login.password.confirm', ['token' => $token]);
$I->submitForm('#form-password-confirm', [
    'email' => $user->email,
    'password' => 'newpassword',
    'password_confirm' => 'newpassword',
]);

$user = User::find($user->id);
$I->assertTrue(password_verify('newpassword', $user->password));
