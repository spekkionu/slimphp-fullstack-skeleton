<?php
/**
 * @group auth
 */
$I = new FunctionalTester($scenario);
$I->wantTo('log out');

$password = 'password';
$user = factory(App\Model\User::class)->create(['password' => password_hash($password, PASSWORD_DEFAULT)]);
$I->amLoggedInAs($user);
$I->seeIAmLoggedInAs($user);
$I->amOnRoute('logout');
$I->seeIAmNotLoggedIn();
