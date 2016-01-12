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
        $form['opportunity[skills][2]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Opportunity added")')->count());
    }

    public function testOrganizationPhoneValidation()
    {
        $crawler = $this->login('jglenshire');
        $crawler = $this->client->request('GET', '/org/edit/1');
        $form = $crawler->selectButton('Save organization')->form();
        $form['org[phone]'] = '123';
        $form['org[areacode]'] = '5';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Phone must be")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Area code must be")')->count());
    }

    public function testOrganizationFocusEdit()
    {
        $crawler = $this->login('jglenshire');
        $crawler = $this->client->request('GET', '/org/edit/1');
        $form = $crawler->selectButton('Save organization')->form();
        $form['org[focuses][0]']->untick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("At least one")')->count());

        $form['org[focuses][3]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("updated")')->count());
    }

    public function testOpportunityEdit()
    {
        $crawler = $this->login('jglenshire');
        $crawler = $this->client->request('GET', '/opp/edit/1');
        $form = $crawler->selectButton('Save opportunity')->form();
        $form['opportunity[skills][1]']->untick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("At least one skill is required")')->count());

        $form['opportunity[skills][2]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("updated")')->count());
    }

    public function testAddNewEvent()
    {
        $crawler = $this->login('jglenshire');
        $link = $crawler->selectLink('Add event')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Submit')->form();
        $form['event[event]'] = 'Ice fishing';
        $form['event[location]'] = 'Donner Lake';
        $form['event[starttime]'] = '3 AM';
        $now = new \DateTime();
        $eventDate = date_format($now, 'm/d/Y');
        $form['event[eventdate]'] = $eventDate;
        $crawler = $this->client->submit($form);
        $link = $crawler->selectLink('Sign out')->link();
        $crawler = $this->client->click($link);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Ice fishing")')->count());
    }
}
