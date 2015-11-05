<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Controller\RegistrationFunctionalTest.php


namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Tests registration process through e-mail confirmation notice
 * Gets confirmation token and confirms registration.
 *
 * @author George
 */
class RegistrationFunctionalTest extends WebTestCase
{
    private $client;

    public function setup()
    {
        $classes = array(
            'Truckee\MatchBundle\DataFixtures\Test\LoadFocusSkillData',
            'Truckee\MatchBundle\DataFixtures\Test\LoadMinimumData',
        );
        $this->loadFixtures($classes);
        $this->client = static::createClient();
    }

    public function submitVolunteerForm()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[email]'] = 'hvolunteer@bogus.info';
        $form['volunteer_registration[username]'] = 'hvolunteer';
        $form['volunteer_registration[firstName]'] = 'Harry';
        $form['volunteer_registration[lastName]'] = 'Volunteer';
        $form['volunteer_registration[plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[plainPassword][second]'] = '123Abcd';
        $form['volunteer_registration[focuses]'] = [1];
        $form['volunteer_registration[skills]'] = [14];

        $crawler = $this->client->submit($form);
    }

    public function activateVolunteer()
    {
        $container = $this->client->getContainer();
        $userManager = $container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('username' => 'hvolunteer'));
        $token = $user->getConfirmationToken();
        $crawler = $this->client->request('GET', "/register/confirm/$token");
    }

    public function submitStaffForm()
    {
        $crawler = $this->client->request('GET', '/register/staff');
        $form = $crawler->selectButton('Save')->form();
        $form['staff_registration[email]'] = 'jglenshire@bogus.info';
        $form['staff_registration[username]'] = 'jglenshire';
        $form['staff_registration[firstName]'] = 'Joe';
        $form['staff_registration[lastName]'] = 'Glenshire';
        $form['staff_registration[plainPassword][first]'] = '123Abcd';
        $form['staff_registration[plainPassword][second]'] = '123Abcd';
        $form['staff_registration[organization][orgName]'] = 'Glenshire Marmot Fund';
        $form['staff_registration[organization][focuses]'] = [1];

        $crawler = $this->client->submit($form);
    }

    public function activateStaff()
    {
        $container = $this->client->getContainer();
        $userManager = $container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('username' => 'jglenshire'));
        $token = $user->getConfirmationToken();
        $crawler = $this->client->request('GET', "/register/confirm/$token");
    }

    public function testRegisterVolunteerRoute()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');

        $this->assertTrue($crawler->filter('html:contains("Focus")')->count() > 0);
    }

    public function testRegisterVolunteer()
    {
        $this->submitVolunteerForm();
        $crawler = $this->client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("The user has been created successfully")')->count() > 0);
    }

    public function testRegisterVolunteerEmail()
    {
        $this->submitVolunteerForm();
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(1, $mailCollector->getMessageCount());
    }

    public function testRegisterVolunteerConfirmed()
    {
        $this->submitVolunteerForm();
        $this->activateVolunteer();
        $crawler = $this->client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("activated")')->count() > 0);
    }

    public function testProfileVolunteer()
    {
        $this->submitVolunteerForm();
        $this->activateVolunteer();
        $crawler = $this->client->request('GET', '/profile/edit');
        $form = $crawler->selectButton('Update')->form();
        $values = $form->getPhpValues();
        $receiveEmail = $values['fos_user_profile_form']['receiveEmail'];

        $this->assertTrue(1 == $receiveEmail);
        $this->assertTrue($crawler->filter('html:contains("Signed in as hvolunteer")')->count() > 0);
    }

    public function testChangePasswordVolunteer()
    {
        $this->submitVolunteerForm();
        $this->activateVolunteer();
        $crawler = $this->client->request('GET', '/profile/change-password');
        $form = $crawler->selectButton('Change password')->form();
        $form['fos_user_change_password_form[current_password]'] = '123Abcd';
        $form['fos_user_change_password_form[plainPassword][first]'] = 'Abcd123';
        $form['fos_user_change_password_form[plainPassword][second]'] = 'Abcd123';
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("The password has been changed")')->count() > 0);
    }

    public function testRegisterStaffRoute()
    {
        $crawler = $this->client->request('GET', '/register/staff');

        $this->assertTrue($crawler->filter('html:contains("Focus")')->count() > 0);
    }

    public function testRegisterStaff()
    {
        $this->submitStaffForm();
        $crawler = $this->client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("The user has been created successfully")')->count() > 0);
    }

    public function testRegisterStaffEmail()
    {
        $this->submitStaffForm();
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');

        $this->assertEquals(2, $mailCollector->getMessageCount());
    }

    public function testRegisterStaffConfirmed()
    {
        $this->submitStaffForm();
        $this->activateStaff();
        $crawler = $this->client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("activated")')->count() > 0);
    }

    public function testProfileStaff()
    {
        $this->submitStaffForm();
        $this->activateStaff();
        $crawler = $this->client->request('GET', '/profile/edit');
        $form = $crawler->selectButton('Update')->form();
        $values = $form->getPhpValues();
        $filled = count($values['staff_profile_form']);

        $this->assertEquals(6, $filled);
        $this->assertTrue($crawler->filter('html:contains("Signed in as jglenshire")')->count() > 0);
    }
}
