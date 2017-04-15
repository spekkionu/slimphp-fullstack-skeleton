<?php
/**
 * @group account
 */
$I = new FunctionalTester($scenario);
$I->wantTo('try to access profile without logging in');

$I->amNotLoggedIn();
$I->amOnRoute('account');
$I->seeInCurrentUrl('login');

$I->amNotLoggedIn();
$I->amOnRoute('account.profile');
$I->seeInCurrentUrl('login');
