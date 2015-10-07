<?php
$I = new PopulatedTester($scenario);
$I->wantTo('Test installation');
$I->amOnPage('/register/volunteer');
$I->see('Skill criteria');
//$I->see('Sign in');