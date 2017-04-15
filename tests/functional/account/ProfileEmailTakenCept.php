<?php
/**
 * @group account
 */
$I = new FunctionalTester($scenario);
$I->wantTo('try to set email to one taken by another account');

/** @var \App\Model\User $user */
$user = factory(App\Model\User::class)->create([
    'email' => 'old@example.com',
]);
/** @var \App\Model\User $other */
$other = factory(App\Model\User::class)->create([
    'email' => 'new@example.com',
]);

$I->amLoggedInAs($user);
$I->amOnRoute('account.profile');
$I->submitForm('#form-profile', [
    'email' => 'new@example.com',
]);
$I->seeInDatabase('users', [
    'id' => $user->id,
    'email' => 'old@example.com',
]);
