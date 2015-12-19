<?php
namespace Step\Acceptance;

class Admin extends \AcceptanceTester
{

    public function loginAsAdmin()
    {
        $I = $this;
        $I->amOnPage('/login');
        $I->fillField('#username', 'admin');
        $I->fillField('#password', '123Abcd');
        $I->click('Login');
    }

}