<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Controller\AdminControllerTest.php


namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Description of TestAdminController.
 *
 * @author George
 */
class AdminControllerTest extends WebTestCase
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
//        $getting = $this->client->getResponse()->getContent();
//        file_put_contents("G:\\Documents\\response.html", $getting);
    }

    public function login($user)
    {
        //        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form();
        $form['_username'] = $user;
        $form['_password'] = '123Abcd';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testAdminHome()
    {
        $crawler = $this->login('admin');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Expiring opportunities")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Incoming opportunities")')->count());
    }

    public function testExpiringAlertEmail()
    {
        $crawler = $this->login('admin');
        $this->client->followRedirects(false);
        $link = $crawler->selectLink('Send alerts to organizations')->link();
        $crawler = $this->client->click($link);
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(2, $mailCollector->getMessageCount());
        $this->client->followRedirects();
    }

    public function testExpiringAlerts()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('Send alerts to organizations')->link();
        $crawler = $this->client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Expiration alerts sent to")')->count());
    }

    public function testShowMatchedVolunteers()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('E-mail volunteers')->link();
        $crawler = $this->client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("hvolunteer")')->count());
    }

    public function testSendVolunteerEmail()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('E-mail volunteers')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Send')->form();
        $form['vol_email[send][0]']->setValue(3);
        $this->client->followRedirects(false);
        $crawler = $this->client->submit($form);
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');

        $this->assertEquals(2, $mailCollector->getMessageCount());
        $this->client->followRedirects();
    }

    public function testActivateOrganization()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('Accept organization')->link();
        $crawler = $this->client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("has been activated")')->count());
    }

    public function testActivateOrganizationEmail()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('Accept organization')->link();
        $this->client->followRedirects(false);
        $crawler = $this->client->click($link);
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');

        $this->assertEquals(1, $mailCollector->getMessageCount());
        $this->client->followRedirects();
    }

    public function testDuplicateReport()
    {
        $crawler = $this->login('admin');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Possibly same as")')->count());
    }

    public function testDropOrganization()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('Drop organization')->link();
        $crawler = $this->client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("has been dropped")')->count());
    }

    public function testOutboxUser()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('Send alerts to organizations')->link();
        $crawler = $this->client->click($link);
        $outboxObj = $this->em->getRepository('TruckeeMatchBundle:AdminOutbox')->findAll();
        $outbox = $outboxObj[0];
        $recipient = $outbox->getRecipientId();
        $type = $this->tool->getTypeFromId($recipient);

        $this->assertEquals('staff', $type);
    }

//    public function organizationSelect()
//    {
//        $crawler = $this->login('admin');
//        $link = $crawler->selectLink("Organizations")->link();
//        return $this->client->click($link);
//    }
//
//    public function testOrganizationSelect()
//    {
//        $crawler = $this->organizationSelect();
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Select organization to edit")')->count());
//    }
//
//    public function organizationEdit()
//    {
//        $crawler = $this->organizationSelect();
//        $form = $crawler->selectButton('Select')->form();
//        $value = $crawler->filter('#org_select_organization option:contains("Glenshire Marmot Fund")')->attr('value');
//        $form['org_select[organization]'] = $value;
//        $crawler = $this->client->submit($form);
//        return $this->client->submit($form);
//    }
//
//    public function testExistingOrganizationEdit()
//    {
//        $crawler = $this->organizationEdit();
//        $form = $crawler->selectButton('Save organization')->form();
//        $form['org[address]'] = 'PO Box 9999';
//        $form['org[city]'] = 'Reno';
//        $form['org[state]'] = 'NV';
//        $form['org[zip]'] = '88888';
//        $form['org[website]'] = 'www.glenshire.org';
//        $crawler = $this->client->submit($form);
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Glenshire Marmot Fund updated")')->count());
//    }
//    
//    public function testOpportunityEdit()
//    {
//        $crawler = $this->login('admin');
//        $link = $crawler->selectLink("Edit opportunity")->link();
//        $crawler = $this->client->click($link);
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Glenshire Marmot Fund: Edit opportunity")')->count());
//    }
//    
//    public function testNewOrganizationEdit()
//    {
//        $crawler = $this->login('admin');
//        $link = $crawler->selectLink("Edit organization")->link();
//        $crawler = $this->client->click($link);
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Edit Glenshire Marmite Fund")')->count());
//    }
//
//    public function testNoOpportunityMatches()
//    {
//        $crawler = $this->login('admin');
//
//        $link = $crawler->selectLink('Accept organization')->link();
//        $crawler = $this->client->click($link);
//        $crawler = $this->login('jmelanzane');
//        $link = $crawler->selectLink('Add opportunity')->link();
//        $crawler = $this->client->click($link);
//        $form = $crawler->selectButton('Save opportunity')->form();
//        $form['opportunity[oppName]'] = 'Meals on Wheels driver';
//        $form['opportunity[description]'] = 'Deliver meals to seniors';
//        $form['opportunity[skills][7]']->tick();
//        $crawler = $this->client->submit($form);
//        $crawler = $this->login('admin');
//        $link = $crawler->filter('a:contains("E-mail volunteers")')->eq(1)->link();
//        $crawler = $this->client->click($link);
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("No volunteers match opportunity criteria")')->count());
//    }
//    
//    public function addAdmin()
//    {
//        $crawler = $this->login('admin');
//        $crawler = $this->client->request('GET', '/register/admin');
//        $form = $crawler->selectButton('Save')->form();
//        $form['fos_user_registration_form[email]'] = 'bborko@bogus.info';
//        $form['fos_user_registration_form[username]'] = 'bborko';
//        $form['fos_user_registration_form[firstName]'] = 'Benny';
//        $form['fos_user_registration_form[lastName]'] = 'Borko';
//        $form['fos_user_registration_form[plainPassword][first]'] = '123Abcd';
//        $form['fos_user_registration_form[plainPassword][second]'] = '123Abcd';
//        
//        return $this->client->submit($form);
//    }
//
//    public function testAdminAdd()
//    {
//        $crawler = $this->addAdmin();
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("The user has been created successfully")')->count());
//    }
//
//    public function testAddStaff()
//    {
//        $crawler = $this->organizationEdit();
//        $link = $crawler->selectLink('Add staff')->link();
//        $crawler = $this->client->click($link);
//        $form = $crawler->selectButton('Save')->form();
//        $form['person_registration[email]'] = 'bborko@bogus.info';
//        $form['person_registration[username]'] = 'bborko';
//        $form['person_registration[firstName]'] = 'Benny';
//        $form['person_registration[lastName]'] = 'Borko';
//        $form['person_registration[plainPassword][first]'] = '123Abcd';
//        $form['person_registration[plainPassword][second]'] = '123Abcd';
//        $crawler = $this->client->submit($form);
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("User Benny Borko created")')->count());
//    }
//
//    public function testAdminUserLock()
//    {
//        $crawler = $this->addAdmin();
//        $crawler = $this->client->request('GET', '/admin/select/admin');
//        $form = $crawler->selectButton('Select')->form();
//        $value = $crawler->filter('#admin_select_user option:contains("Borko, Benny")')->attr('value');
//        $form['admin_select[user]'] = $value;
//        $crawler = $this->client->submit($form);
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("User Benny Borko updated")')->count());
//    }
//
//    public function testVolunteerUserLock()
//    {
//        $crawler = $this->addAdmin();
//        $crawler = $this->client->request('GET', '/admin/select/volunteer');
//        $form = $crawler->selectButton('Select')->form();
//        $value = $crawler->filter('#vol_select_user option:contains("Volunteer, Harry")')->attr('value');
//        $form['vol_select[user]'] = $value;
//        $crawler = $this->client->submit($form);
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("User Harry Volunteer updated")')->count());
//    }
//
//    public function testStaffUserLock()
//    {
//        $crawler = $this->organizationEdit();
//        $link = $crawler->filter('a:contains("Lock account")')->eq(0)->link();
//        $crawler = $this->client->click($link);
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Cannot")')->count());
//    }
//
//    public function testEditProfile()
//    {
//        $crawler = $this->login('admin');
//        $crawler = $this->client->request('GET', '/profile/edit');
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Current password")')->count());
//    }
//
//    public function testChangePassword()
//    {
//        $crawler = $this->login('admin');
//        $crawler = $this->client->request('GET', '/profile/change-password');
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Password change form")')->count());
//    }
}
