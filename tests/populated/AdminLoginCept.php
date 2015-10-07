<?php 
$I = new PopulatedTester($scenario);
$I->wantTo('Test admin login');
$I->amOnPage('/login');
$I->fillField('#username', 'admin');
$I->fillField('#password', '123Abcd');
$I->click("#_submit");
$I->see('Signed in as admin');
