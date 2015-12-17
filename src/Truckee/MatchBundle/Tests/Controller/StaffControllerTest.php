<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Controller\StaffControllerTest.php

namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * StaffControllerTest
 *
 */
class StaffControllerTest extends WebTestCase
{
    private $client;

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
        $this->client = $this->createClient();
        $this->client->followRedirects();
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

    public function testOpportunityAdd()
    {
        $crawler = $this->login('jglenshire');
        $crawler = $this->client->request('GET', '/opp/new');
        $form = $crawler->selectButton('Save opportunity')->form();
        $form['opportunity[active]']->tick();
        $form['opportunity[oppName]'] = 'Frog';
        $form['opportunity[description]'] = 'Knee deep';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Opportunity added")')->count());
    }

    public function testOrganizationPhoneValidation()
    {
        $crawler = $this->login('jglenshire');
        $crawler = $this->client->request('GET', '/org/edit/1');
        $form = $crawler->selectButton('Save organization')->form();
        $form['org[phone]'] = '123';
        $form['org[areacode]'] = '5';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Phone must be")')->count());
        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Area code must be")')->count());
    }
}
