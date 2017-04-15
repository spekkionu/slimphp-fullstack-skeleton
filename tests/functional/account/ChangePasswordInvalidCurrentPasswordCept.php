<?php
/**
 * @group account
 */
use App\Model\User;

$I = new FunctionalTester($scenario);
$I->wantTo('try to change password with incorrect old password');

/** @var \App\Model\User $user */
$user = factory(App\Model\User::class)->create([
    'password' => password_hash('oldpassword', PASSWORD_DEFAULT)
]);

$I->amLoggedInAs($user);
$I->amOnRoute('account.password');
$I->submitForm('#form-password', [
    'current' => 'incorrectpassword',
    'password' => 'newpassword',
    'password_confirm' => 'newpassword',
]);

$user = User::find($user->id);
$I->assertFalse(password_verify('newpassword', $user->password));
$I->assertTrue(password_verify('oldpassword', $user->password));
