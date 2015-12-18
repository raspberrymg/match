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
        $this->mailer = static::$kernel->getContainer()
            ->get('admin.mailer')
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

    public function testAdminHome()
    {
        $crawler = $this->login('admin');

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Expiring opportunities")')->count());
        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Incoming opportunities")')->count());
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
        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Expiration alerts sent to")')->count());
    }

    public function testShowMatchedVolunteers()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('E-mail volunteers')->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(4,
            $crawler->filter('div:contains("Harry")')->count());
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
        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("has been activated")')->count());
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
        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Possibly same as")')->count());
    }

    public function testDropOrganization()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('Drop organization')->link();
        $crawler = $this->client->click($link);
        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("has been dropped")')->count());
    }

    public function testAdminOutbox()
    {
        $crawler = $this->login('admin');
        $link = $crawler->selectLink('Send alerts to organizations')->link();
        $crawler = $this->client->click($link);
        $outboxObj = $this->em->getRepository('TruckeeMatchBundle:AdminOutbox')->findAll();
        $outbox = $outboxObj[0];

        $userType = $outbox->getUserType();
        $this->assertEquals('staff', $userType);

        $messageType = $outbox->getMessageType();
        $this->assertEquals('to', $messageType);

        $orgId = $outbox->getOrgId();
        $this->assertEquals('1', $orgId);

        $oppId = $outbox->getoppId();
        $this->assertEquals('1', $oppId);

        $function = $outbox->getFunction();
        $this->assertEquals('expiringAlertsAction', $function);

        $date = $outbox->getDate();
        $current = new \DateTime();
        $this->assertEquals(date_format($current, 'Y-m-d'), date_format($date, 'Y-m-d'));
    }

    public function testExistingOrganizationEdit()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET', '/org/edit/1');
        $form = $crawler->selectButton('Save organization')->form();
        $form['org[address]'] = 'PO Box 9999';
        $form['org[city]'] = 'Reno';
        $form['org[state]'] = 'NV';
        $form['org[zip]'] = '88888';
        $form['org[website]'] = 'www.glenshire.org';
        $form['org[email]'] = 'info@glenshire.org';
        $form['org[phone]'] = '123-4567';
        $form['org[areacode]'] = '555';
        $form['org[focuses][6]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Glenshire Marmot Fund updated")')->count());
    }

    public function addStaff()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET',
            '/admin/addStaff/1');
        $form = $crawler->selectButton('Save')->form();
        $form['person_add[personData][email]'] = 'dingus@bogus.info';
        $form['person_add[personData][username]'] = 'dingus';
        $form['person_add[personData][firstName]'] = 'Dorkus';
        $form['person_add[personData][lastName]'] = 'Ingus';
        $form['person_add[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['person_add[registerPassword][plainPassword][second]'] = '123Abcd';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testAddStaff()
    {
        $crawler = $this->addStaff();

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("User Dorkus Ingus created")')->count());
    }

    public function testAddStaffEmail()
    {
        $this->client->followRedirects(false);
        $crawler = $this->addStaff();
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');

        $this->assertEquals(1, $mailCollector->getMessageCount());
        $this->client->followRedirects();
    }

    private function addAdmin()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET',
            '/admin/addAdmin');
        $form = $crawler->selectButton('Save')->form();
        $form['person_add[personData][email]'] = 'dingus@bogus.info';
        $form['person_add[personData][username]'] = 'dingus';
        $form['person_add[personData][firstName]'] = 'Dorkus';
        $form['person_add[personData][lastName]'] = 'Ingus';
        $form['person_add[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['person_add[registerPassword][plainPassword][second]'] = '123Abcd';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testAddAdmin()
    {
        $crawler = $this->addAdmin();

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("User Dorkus Ingus created")')->count());
    }

    public function testAddAdminEmail()
    {
        $this->client->followRedirects(false);
        $crawler = $this->addAdmin();
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');

        $this->assertEquals(1, $mailCollector->getMessageCount());
        $this->client->followRedirects();
    }

    public function testLockStaff()
    {
        //lock second of two staff members
        $before = $this->em->getRepository('TruckeeMatchBundle:Person')->findAll(array(
            'locked' => 1, ));
        $crawler = $this->addStaff();
        $crawler = $this->client->request('GET',
            '/admin/lock/8');
        $after = $this->em->getRepository('TruckeeMatchBundle:Person')->findAll(array(
            'locked' => true, ));

        $this->assertEquals(1, count($after) - count($before));

        //cannot lock only staff member
        $crawler = $this->client->request('GET',
            '/admin/lock/2');
        $later = $this->em->getRepository('TruckeeMatchBundle:Person')->findAll(array(
            'locked' => true, ));

        $this->assertEquals(count($after), count($later));

        //cannot lock super_admin
        $crawler = $this->client->request('GET',
            '/admin/lock/1');
        $later = $this->em->getRepository('TruckeeMatchBundle:Person')->findAll(array(
            'locked' => true, ));

        $this->assertEquals(count($after), count($later));
    }

    public function testAddFocus()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET','/editFocus');
        $form = $crawler->selectButton('Save')->form();
        $form['focus[focus]'] = 'Baloney';
        $form['focus[enabled]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Focus criteria updated")')->count());
    }

    public function testAddSkill()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET','/editSkill');
        $form = $crawler->selectButton('Save')->form();
        $form['skill[skill]'] = 'Baloney';
        $form['skill[enabled]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Skill criteria updated")')->count());
    }

    public function testStaffHome()
    {
        $crawler = $this->login('admin');
        $crawler = $this->client->request('GET','/staffhome');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("staff only please")')->count());
    }

    public function testVolunteerUserLock()
    {
        $crawler = $this->addAdmin();
        $crawler = $this->client->request('GET', '/admin/select/volunteer');
        $form = $crawler->selectButton('Select')->form();
        $value = $crawler->filter('#vol_select_user option:contains("Volunteer, Harry")')->attr('value');
        $form['vol_select[user]'] = $value;
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("User Harry Volunteer updated")')->count());
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
