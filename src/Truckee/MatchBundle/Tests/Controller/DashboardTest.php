<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Controller\DashboardTest.php

namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * DashboardTest
 *
 */
class DashboardTest extends WebTestCase
{

    private $client;
    private $em;
    private $tool;

    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager()
        ;
        $this->tool = static::$kernel->getContainer()
                ->get('truckee_match.toolbox')
        ;

        $classes = array(
            'Truckee\MatchBundle\DataFixtures\Test\LoadFocusSkillData',
            'Truckee\MatchBundle\DataFixtures\Test\LoadMinimumData',
            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserGlenshire',
            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserMelanzane',
            'Truckee\MatchBundle\DataFixtures\Test\LoadOpportunity',
            'Truckee\MatchBundle\DataFixtures\Test\LoadVolunteer',
            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserBorko',
            'Truckee\MatchBundle\DataFixtures\Test\LoadTurkeyOpportunity',
        );
        $this->loadFixtures($classes);
        $this->client = $this->createClient();
        $this->client->followRedirects();
//        file_put_contents("G:\\Documents\\response.html", $this->client->getResponse()->getContent());
    }

    public function login($user)
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form();
        $form['_username'] = $user;
        $form['_password'] = '123Abcd';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testEmptyDashboardEmail()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(0, trim($crawler->filter('div#oppSearchForm30Day')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#oppSearchFormAll')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#newOppEmails30Day')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#newOppEmails')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#expiringOppEmails30Day')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#expiringOppEmails')->text()));
    }

    public function testVolunteerRegistration()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(0, trim($crawler->filter('div#newVols30Day ')->text()));
        $this->assertEquals(3, trim($crawler->filter('div#newVols')->text()));
    }

    public function testOrganizationDashboard()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(2, trim($crawler->filter('div#newOrg30Day')->text()));
        $this->assertEquals(3, trim($crawler->filter('div#newOrg')->text()));
    }

    public function testOpportunityDashboard()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(2, trim($crawler->filter('div#newOpps30Day')->text()));
        $this->assertEquals(2, trim($crawler->filter('div#newOpps')->text()));
    }

    public function testVolunteerStatus()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(3, trim($crawler->filter('div#volReceivingMailOn')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#volReceivingMailOff')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#volLocked')->text()));
    }

    public function testOrganizationStatusDashboard()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(3, trim($crawler->filter('div#orgActive')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#orgInactive')->text()));
    }

    public function testOppStatus()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(2, trim($crawler->filter('div#oppActive')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#oppInactive')->text()));
        $this->assertEquals(0, trim($crawler->filter('div#oppExpired')->text()));
    }

    public function testVolunteersEmailDashboard()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink("E-mail volunteers")->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Send')->form();
        $form['vol_email[send][0]']->setValue(3);
        $crawler = $this->client->submit($form);
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(1, trim($crawler->filter('div#newOppEmails30Day')->text()));
        $this->assertEquals(1, trim($crawler->filter('div#newOppEmails')->text()));
    }

    public function testExpiringAlertsEmailDashboard()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink("Send alerts to organizations")->link();
        $crawler = $this->client->click($link);
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(2, trim($crawler->filter('div#expiringOppEmails30Day')->text()));
        $this->assertEquals(2, trim($crawler->filter('div#expiringOppEmails')->text()));
    }

    public function submitVolunteerForm()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][email]'] = 'hvolunteer@bogus.info';
        $form['volunteer_registration[personData][username]'] = 'hvolunteer';
        $form['volunteer_registration[personData][firstName]'] = 'Harry';
        $form['volunteer_registration[personData][lastName]'] = 'Volunteer';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Abcd';
        $form['volunteer_registration[focuses]'] = [2];
        $form['volunteer_registration[skills]'] = [15];

        $crawler = $this->client->submit($form);
    }

    public function activateVolunteer()
    {
        $container = $this->client->getContainer();
        $userManager = $container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('username' => 'hvola'));
        $token = $user->getConfirmationToken();
        $crawler = $this->client->request('GET', "/register/confirm/$token");
    }

    public function testVolunteerRegistrationDashboard()
    {
        $this->submitVolunteerForm();
        $this->activateVolunteer();
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $this->assertEquals(0, trim($crawler->filter('div#newVols30Day ')->text()));
        $this->assertEquals(3, trim($crawler->filter('div#newVols')->text()));
    }
}
