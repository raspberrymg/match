<?php 
$I = new PopulatedTester($scenario);
$I->wantTo('Test admin login');
$I->amOnPage('/login');
$I->fillField('_username', 'bborko');
$I->fillField('_password', '123Abcd');
