<?php
//tests\acceptance\004_AdminSelectAllCept.php


use Step\Acceptance\Admin as AdminTester;

$I = new AdminTester($scenario);

$I->wantTo('Test select all function');
$I->loginAsAdmin();
$I->click("E-mail volunteers");
$I->see("Select/unselect all");
$I->click("#vol_email_selectAll");
$I->seeCheckboxIsChecked('vol_email[send][]');
