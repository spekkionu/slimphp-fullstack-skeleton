<?php
/**
 * @group account
 */
$I = new FunctionalTester($scenario);
$I->wantTo('update my profile');

/** @var \App\Model\User $user */
$user = factory(App\Model\User::class)->create([
    'email' => 'steve@example.com',
    'name' => 'Steve',
    'password' => password_hash('password', PASSWORD_DEFAULT)
]);
$I->amLoggedInAs($user);
$I->amOnRoute('account.profile');
$I->submitForm('#form-profile', [
    'name' => 'Bob',
    'email' => 'bob@example.com',
]);
$I->seeInDatabase('users', [
    'id' => $user->id,
    'email' => 'bob@example.com',
    'name' => 'Bob'
]);
