<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Validation\VolunteerValidationTest.php


namespace Truckee\MatchBundle\Tests\Validation;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * VolunteerValidationTest.
 */
class VolunteerValidationTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        self::bootKernel();
        $classes = array(
            'Truckee\MatchBundle\DataFixtures\Test\LoadFocusSkillData',
            'Truckee\MatchBundle\DataFixtures\Test\LoadMinimumData',
        );
        $this->loadFixtures($classes);
        $this->client = $this->createClient();
        $this->client->followRedirects();
    }

    public function testVolunteerEmail()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][username]'] = 'hvolunteer';
        $form['volunteer_registration[personData][firstName]'] = 'Harry';
        $form['volunteer_registration[personData][lastName]'] = 'Volunteer';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Abcd';
        $form['volunteer_registration[focuses]'] = [2];
        $form['volunteer_registration[skills]'] = [15];
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Please enter an email address")')->count());
    }

    public function testVolunteerEmailTaken()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][email]'] = 'admin@bogus.info';
        $form['volunteer_registration[personData][username]'] = 'hvolunteer';
        $form['volunteer_registration[personData][firstName]'] = 'Harry';
        $form['volunteer_registration[personData][lastName]'] = 'Volunteer';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Abcd';
        $form['volunteer_registration[focuses]'] = [2];
        $form['volunteer_registration[skills]'] = [15];
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Address already exists")')->count());
    }

    public function testVolunteerUserName()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][email]'] = 'hvolunteer@bogus.info';
        $form['volunteer_registration[personData][firstName]'] = 'Harry';
        $form['volunteer_registration[personData][lastName]'] = 'Volunteer';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Abcd';
        $form['volunteer_registration[focuses]'] = [2];
        $form['volunteer_registration[skills]'] = [15];
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Please enter a username")')->count());
    }

    public function testVolunteerUserNameTaken()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][email]'] = 'hvolunteer@bogus.info';
        $form['volunteer_registration[personData][username]'] = 'admin';
        $form['volunteer_registration[personData][firstName]'] = 'Harry';
        $form['volunteer_registration[personData][lastName]'] = 'Volunteer';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Abcd';
        $form['volunteer_registration[focuses]'] = [2];
        $form['volunteer_registration[skills]'] = [15];
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Username already exists")')->count());
    }

    public function testVolunteerFirstName()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][email]'] = 'hvolunteer@bogus.info';
        $form['volunteer_registration[personData][username]'] = 'hvolunteer';
        $form['volunteer_registration[personData][lastName]'] = 'Volunteer';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Abcd';
        $form['volunteer_registration[focuses]'] = [2];
        $form['volunteer_registration[skills]'] = [15];
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("First name is required")')->count());
    }

    public function testVolunteerLastName()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][email]'] = 'hvolunteer@bogus.info';
        $form['volunteer_registration[personData][username]'] = 'hvolunteer';
        $form['volunteer_registration[personData][firstName]'] = 'Harry';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Abcd';
        $form['volunteer_registration[focuses]'] = [2];
        $form['volunteer_registration[skills]'] = [15];
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Last name is required")')->count());
    }

    public function testVolunteerFocusSkill()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][email]'] = 'hvolunteer@bogus.info';
        $form['volunteer_registration[personData][username]'] = 'hvolunteer';
        $form['volunteer_registration[personData][firstName]'] = 'Harry';
        $form['volunteer_registration[personData][lastName]'] = 'Volunteer';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Abcd';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("At least one focus")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("At least one skill")')->count());
    }

    public function testVolunteerPasswordMatch()
    {
        $crawler = $this->client->request('GET', '/register/volunteer');
        $form = $crawler->selectButton('Save')->form();
        $form['volunteer_registration[personData][email]'] = 'hvolunteer@bogus.info';
        $form['volunteer_registration[personData][username]'] = 'hvolunteer';
        $form['volunteer_registration[personData][firstName]'] = 'Harry';
        $form['volunteer_registration[personData][lastName]'] = 'Volunteer';
        $form['volunteer_registration[registerPassword][plainPassword][first]'] = '123Abcd';
        $form['volunteer_registration[registerPassword][plainPassword][second]'] = '123Bcd';
        $form['volunteer_registration[focuses]'] = [2];
        $form['volunteer_registration[skills]'] = [15];
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("entered passwords don\'t match")')->count());
    }
}
