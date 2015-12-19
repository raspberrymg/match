<?php
//tests\acceptance\002_SecondOpportunityEmailCept.php

$I = new AcceptanceTester($scenario);

$I->wantTo('Check opportunity e-mail function');
$I->amOnPage('/search');
$I->see("Opportunity search criteria");
$I->click('Search');
$I->see("E-mail Turkeys R Us");
$I->click('E-mail Turkeys R Us');
$I->waitForJS("return $.active == 0;", 3);
$I->see("Message");
$I->click('Send');
$I->waitForJS("return $.active == 0;", 3);
$I->see("Message is required");
$I->fillField("#opp_email_from", "elmo@bogus.info");
$I->fillField("#opp_email_message", "I wuv U");
$I->click('Send');
$I->waitForJS("return $.active == 0;", 3);
$I->see("Email sent");

