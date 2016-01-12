<?php
/*
 * This file is part of the Truckee\Match package.
 *
 * (c) George W. Brooks
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Controller\FocusSkillTTTest.php

namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class FocusSkillTTTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        self::bootKernel();
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
        $this->client = $this->createClient();
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

    public function testSearchFocusSkill_TT()
    {
        $crawler = $this->client->request('GET', '/search');
        $this->assertTrue($crawler->filter('html:contains("Focus")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Skill")')->count() == 1);
    }

    public function testFocus()
    {
        $this->client->followRedirects();
        $crawler = $this->adminLogin();
        $crawler = $this->client->request('GET', '/editFocus');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Animal Welfare")')->count());
        $this->client->followRedirects(false);
    }

    public function testSkill()
    {
        $this->client->followRedirects();
        $crawler = $this->adminLogin();
        $crawler = $this->client->request('GET', '/editSkill');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Driving")')->count());
        $this->client->followRedirects(false);
    }

    public function testRegisterVolunteerFocus()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Animal Welfare")')->count());
    }

    public function testOrganizationEditFocus()
    {
        $this->client->followRedirects();
        $crawler = $this->adminLogin();
        $crawler = $this->client->request('GET', '/org/edit/1');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Animal Welfare")')->count());
        $this->client->followRedirects(false);
    }

    public function testRegisterVolunteerSkill()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');

        $this->assertEquals(1, $crawler->filter('html:contains("Skill")')->count());
    }

    public function testOpportunityEditSkill()
    {
        $this->client->followRedirects();
        $crawler = $this->adminLogin();
        $crawler = $this->client->request('GET', '/opp/edit/1');

        $this->assertEquals(1, $crawler->filter('html:contains("Skill")')->count());
        $this->client->followRedirects(false);
    }
}
