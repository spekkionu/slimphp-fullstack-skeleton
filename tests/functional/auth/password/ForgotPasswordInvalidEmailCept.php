<?php
/**
 * @group password
 */
$I = new FunctionalTester($scenario);
$I->wantTo('try to reset password without account');

$I->dontSeeInDatabase('password_resets', ['email' => 'bob@example.com']);
$I->amOnRoute('login.password');
$I->submitForm('#form-forgot-password', [
    'email' => 'cheese@example.com',
]);
$I->dontSeeInDatabase('password_resets', ['email' => 'bob@example.com']);
