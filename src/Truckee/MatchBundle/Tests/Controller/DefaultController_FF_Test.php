<?php

namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultController_FF_Test extends WebTestCase
{
    private $client;
    private $em;
    private $skillRequired;

    public function setUp()
    {
        self::bootKernel();
        $this->em     = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        $classes      = array(
            'Truckee\MatchBundle\DataFixtures\Test\LoadFocusSkillData',
            'Truckee\MatchBundle\DataFixtures\Test\LoadMinimumData',
//            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserGlenshire',
//            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserMelanzane',
//            'Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserBorko',
//            'Truckee\MatchBundle\DataFixtures\Test\LoadTurkeyOpportunity',
//            'Truckee\MatchBundle\DataFixtures\Test\LoadOpportunity',
        );
        $this->loadFixtures($classes);
        $this->client = $this->createClient(array('environment' => 'test_FF'));
//        $this->client->followRedirects();
//        $getting = $client->getResponse()->getContent();
//        file_put_contents("G:\\Documents\\response.html", $getting);
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony',
            $crawler->filter('#container h1')->text());
        $this->assertTrue($this->focusRequired);
    }


    public function testSearchSkill_FF()
    {
        $kernel = new \AppKernel('test_FF', true);
        $kernel->boot();
        $client = $this->createClient(array('environment' => 'test_FF'));

        $crawler = $client->request('GET', '/search');
        $this->assertTrue($crawler->filter('html:contains("Focus")')->count() == 0);
        $this->assertTrue($crawler->filter('html:contains("Skill")')->count() == 0);
    }
}
