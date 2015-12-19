<?php
//tests\acceptance\001_AdminDashboardCept.php

use Step\Acceptance\Admin as AdminTester;

$I = new AdminTester($scenario);

$I->wantTo('Test admin dashboard');
$I->loginAsAdmin();
$I->see("Signed in as admin");
$I->click("Dashboard");
$I->see("E-mail from search form");
$count = $I->grabTextFrom("#oppSearchFormAll");
$I->assertEquals(trim($count), 0, 'E-mails not equal 0');


$I->wantTo('Check opportunity e-mail function');
$I->amOnPage('/search');
$I->see("Opportunity search criteria");
$I->click('Search');
$I->see("E-mail Glenshire Marmot Fund");
$I->click('E-mail Glenshire Marmot Fund');
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

$I->wantTo('Test admin dashboard');
$I->loginAsAdmin();
$I->see("Signed in as admin");
$I->click("Dashboard");
$I->see("E-mail from search form");
$count = $I->grabTextFrom("#oppSearchFormAll");
$I->assertEquals(trim($count), 1, 'E-mails not equal 1');
