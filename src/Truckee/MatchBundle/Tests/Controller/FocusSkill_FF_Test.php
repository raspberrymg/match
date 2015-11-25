<?php

namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class FocusSkill_FF_Test extends WebTestCase
{
    private $client;
    private $em;

    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        $classes = array(
            'Truckee\MatchBundle\DataFixtures\Test\LoadFocusSkillData',
            'Truckee\MatchBundle\DataFixtures\Test\LoadMinimumData',
            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserGlenshire',
            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserMelanzane',
            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserBorko',
            'Truckee\MatchBundle\DataFixtures\Test\LoadTurkeyOpportunity',
            'Truckee\MatchBundle\DataFixtures\Test\LoadOpportunity',
        );
        $this->loadFixtures($classes);
        $this->client = $this->createClient(array('environment' => 'test_FF'));
    }

    public function adminLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form();
        $form['_username'] = 'admin';
        $form['_password'] = '123Abcd';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testSearchFocusSkill_FF()
    {
        $kernel = new \AppKernel('test_FF', true);
        $kernel->boot();

        $crawler = $this->client->request('GET', '/search');
        $this->assertTrue($crawler->filter('html:contains("Focus")')->count() == 0);
        $this->assertTrue($crawler->filter('html:contains("Skill")')->count() == 0);
    }

    public function testNoFocus()
    {
        $this->client->followRedirects();
        $crawler = $this->adminLogin();
        $crawler = $this->client->request('GET', '/editFocus');

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Focus criteria not enabled")')->count());
        $this->client->followRedirects(false);
    }

    public function testNoSkill()
    {
        $this->client->followRedirects();
        $crawler = $this->adminLogin();
        $crawler = $this->client->request('GET', '/editSkill');

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Skill criteria not enabled")')->count());
        $this->client->followRedirects(false);
    }

    public function testRegisterVolunteerFocus()
    {
        $crawler = $this->client->request('GET', '/register/staff');

        $this->assertEquals(0,
            $crawler->filter('html:contains("Focus")')->count());
    }

    public function testOrganizationEditFocus()
    {
        $crawler = $this->client->request('GET', '/org/edit/1');

        $this->assertEquals(0,
            $crawler->filter('html:contains("Focus")')->count());
    }

    public function testRegisterVolunteerSkill()
    {
        $crawler = $this->client->request('GET', '/register/staff');

        $this->assertEquals(0,
            $crawler->filter('html:contains("Skill")')->count());
    }

    public function testOpportunityEditSkill()
    {
        $crawler = $this->client->request('GET', '/opp/edit/1');

        $this->assertEquals(0,
            $crawler->filter('html:contains("Skill")')->count());
    }
}
