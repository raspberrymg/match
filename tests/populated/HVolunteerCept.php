<?php 
$I = new PopulatedTester($scenario);
$I->wantTo('Test admin login');
$I->amOnPage('/login');
$I->fillField('_username', 'hvolunteer');
$I->fillField('_password', '123Abcd');
