<?php

/*
 * This file is part of the Truckee\Match package.
 *
 * (c) George W. Brooks
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Controller\DefaultControllerTest.php

namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client;
    private $em;
    private $focusRequired;

    public function setUp()
    {
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

        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        $this->focusRequired = static::$kernel->getContainer()
            ->getParameter('focus_required')
        ;
        $this->skillRequired = static::$kernel->getContainer()
            ->getParameter('skill_required')
        ;
        $this->client = $this->createClient();
        $this->client->followRedirects();
    }

    private function login($user)
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form();
        $form['_username'] = $user;
        $form['_password'] = '123Abcd';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testHome()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertGreaterThan(0,
            $crawler->filter("html:contains('Org name here')")->count());
    }

    public function testAboutUs()
    {
        $crawler = $this->client->request('GET', '/about-us');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testContactUs()
    {
        $crawler = $this->client->request('GET', '/contact-us');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testNonprofits()
    {
        $crawler = $this->client->request('GET', '/non-profits');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testSearchNoCriteria()
    {
        $crawler = $this->client->request('GET', '/volunteer');
        $link = $crawler->selectLink('Search for opportunities')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Search')->form();
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Glenshire Marmot Fund")')->count());
    }

    public function testSearchFailingFocusCriterion()
    {
        $crawler = $this->client->request('GET', '/volunteer');
        $link = $crawler->selectLink('Search for opportunities')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Search')->form();
        $form['match_search[focuses][4]']->tick();
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("No opportunities")')->count());
    }

    public function testSearchFailingSkillCriterion()
    {
        $crawler = $this->client->request('GET', '/volunteer');
        $link = $crawler->selectLink('Search for opportunities')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Search')->form();
        $form['match_search[skills][4]']->tick();
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("No opportunities")')->count());
    }

    public function testSearchSuccessfulOrgCriterion()
    {
        $crawler = $this->client->request('GET', '/volunteer');
        $link = $crawler->selectLink('Search for opportunities')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Search')->form();
        $value = $crawler->filter('#match_search_organization_organization option:contains("Glenshire Marmot Fund")')->attr('value');
        $form['match_search[organization][organization]'] = $value;
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Glenshire Marmot Fund")')->count());
    }

    public function testSearchSuccessfulOrgRecord()
    {
        $crawler = $this->client->request('GET', '/volunteer');
        $link = $crawler->selectLink('Search for opportunities')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Search')->form();
        $value = $crawler->filter('#match_search_organization_organization option:contains("Glenshire Marmot Fund")')->attr('value');
        $form['match_search[organization][organization]'] = $value;
        $crawler = $this->client->submit($form);
        $records = $this->em->getRepository('TruckeeMatchBundle:Search')->findAll();
        $this->assertEquals(1, count($records));
    }

    public function testSearchSuccessfulFocusCriterion()
    {
        $crawler = $this->client->request('GET', '/volunteer');
        $link = $crawler->selectLink('Search for opportunities')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Search')->form();
        $form['match_search[focuses][1]']->tick();
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Glenshire Marmot Fund")')->count());
    }

    public function testSearchSuccessfulSkillCriterion()
    {
        $crawler = $this->client->request('GET', '/volunteer');
        $link = $crawler->selectLink('Search for opportunities')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Search')->form();
        $form['match_search[skills][1]']->tick();
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Glenshire Marmot Fund")')->count());
    }

    public function testSearchSuccessfulSkillRecords()
    {
        $crawler = $this->client->request('GET', '/volunteer');
        $link = $crawler->selectLink('Search for opportunities')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Search')->form();
        $form['match_search[skills][1]']->tick();
        $form['match_search[skills][2]']->tick();
        $crawler = $this->client->submit($form);
        $records = $this->em->getRepository('TruckeeMatchBundle:Search')->findAll();
        $this->assertEquals(2, count($records));
    }

    public function testVolunteerLogin()
    {
        $crawler = $this->login('hvolunteer');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Signed in as hvolunteer")')->count());
    }

    public function testStaffLogin()
    {
        $crawler = $this->login('jglenshire');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Signed in as jglenshire")')->count());
    }

    public function testEmailOrganization()
    {
        $this->client->followRedirects(false);
        $crawler = $this->client->request('GET', '/oppForm/1');
        //Note: 'Mail' button is invisible
        $form = $crawler->selectButton('Mail')->form();
        $form['opp_email[to]'] = 'admin@bogus.info';
        $form['opp_email[from]'] = 'admin@bogus.info';
        $form['opp_email[subject]'] = 'Test message';
        $form['opp_email[message]'] = 'Welcome to the zoo';
        $crawler = $this->client->submit($form);
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');

        $this->assertEquals(1, $mailCollector->getMessageCount());
    }
}
