<?php
/**
 * @group account
 */
use App\Model\User;

$I = new FunctionalTester($scenario);
$I->wantTo('change my password');

/** @var \App\Model\User $user */
$user = factory(App\Model\User::class)->create([
    'password' => password_hash('oldpassword', PASSWORD_DEFAULT)
]);

$I->amLoggedInAs($user);
$I->amOnRoute('account.password');
$I->submitForm('#form-password', [
    'current' => 'oldpassword',
    'password' => 'newpassword',
    'password_confirm' => 'newpassword',
]);

$user = User::find($user->id);
$I->assertTrue(password_verify('newpassword', $user->password));
