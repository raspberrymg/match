<?php 
$I = new PopulatedTester($scenario);
$I->wantTo('Test jmelanzane login');
$I->amOnPage('/login');
$I->fillField('_username', 'jmelanzane');
$I->fillField('_password', '123Abcd');
$I->click("Login");
$I->see('Signed in as jmelanzane');
