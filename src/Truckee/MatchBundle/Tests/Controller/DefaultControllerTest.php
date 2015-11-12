<?php

namespace Truckee\MatchBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client;
    private $em;
    private $focusRequired;

    public function setUp()
    {
        self::bootKernel();
        $this->em            = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        self::bootKernel();
        $this->focusRequired = static::$kernel->getContainer()
            ->getParameter('focus_required')
        ;
        self::bootKernel();
        $this->skillRequired = static::$kernel->getContainer()
            ->getParameter('skill_required')
        ;
//        $classes = array(
//            'Truckee\VolunteerBundle\DataFixtures\SampleData\LoadFocusSkillData',
//            'Truckee\VolunteerBundle\DataFixtures\SampleData\LoadMinimumData',
//            'Truckee\VolunteerBundle\DataFixtures\SampleData\LoadStaffUserGlenshire',
//            'Truckee\VolunteerBundle\DataFixtures\SampleData\LoadStaffUserMelanzane',
//            'Truckee\VolunteerBundle\DataFixtures\SampleData\LoadOpportunity',
//            'Truckee\VolunteerBundle\DataFixtures\SampleData\LoadVolunteer',
//        );
//        $this->loadFixtures($classes);
        $this->client        = $this->createClient();
        $this->client->followRedirects();
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony',
            $crawler->filter('#container h1')->text());
    }

    public function testTF()
    {
        $kernel = new \AppKernel('test_TF', true);
        $kernel->boot();
        $client = static::createClient(array('environment' => 'test_TF'));
        $skillRequired = static::$kernel->getContainer()
            ->getParameter('skill_required')
        ;

//        $crawler = $client->request('GET', '/');
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
        $this->assertFalse($skillRequired);
    }
}
