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
}
