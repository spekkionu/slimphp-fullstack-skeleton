<?php
/**
 * @group account
 */
$I = new FunctionalTester($scenario);
$I->wantTo('try to access change password without logging in');

$I->amOnRoute('account.password');
$I->seeInCurrentUrl('login');
