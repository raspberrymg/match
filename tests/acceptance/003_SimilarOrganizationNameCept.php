<?php
//tests\acceptance\003_SimilarOrganizationName.php

$I = new AcceptanceTester($scenario);

$I->wantTo('Check similar organization name function');
$I->amOnPage('/register/staff');
$I->fillField("#staff_registration_organization_orgName", "Glenshire Maronite Fund");
$I->pressKey("#staff_registration_organization_orgName", "\xEE\x80\x84");
$I->waitForJS("return $.active == 0;", 3);
$I->see("Glenshire Marmot Fund");
$I->checkOption("#orgNotListed");
$I->waitForJS("return $.active == 0;", 3);
$I->dontSee("Glenshire Marmot Fund");
$orgName = $I->grabValueFrom("#staff_registration_organization_orgName");
$this->assertEquals($orgName, "Glenshire Maronite Fund");
