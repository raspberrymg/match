<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Controller\SearchTest.php

namespace Truckee\MatchBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Truckee\MatchBundle\Entity\Search;

/**
 * OppSearchTest
 *
 */
class OppSearchTest extends WebTestCase
{

    private $em;

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
    }

    private function addSearch($opportunity) {
        $organization = $opportunity->getOrganization();
        $skills = $opportunity->getSkills();
        $focuses = $organization->getFocuses();

        $search = new Search();
        $search->setDate(new \DateTime());
        $search->setType('opportunity');
        $search->setOrganization($organization);
        $search->setOpportunity($opportunity);
        foreach ($focuses as $focus) {
            $searchClone = clone $search;
            $searchClone->setFocus($focus);
            $this->em->persist($searchClone);
        }
        foreach ($skills as $skill) {
            $searchClone = clone $search;
            $searchClone->setSkill($skill);
            $this->em->persist($searchClone);
        }

        if (!isset($searchClone)) {
            $this->em->persist($search);
        }
        $this->em->flush();
    }


    public function testAddSearch()
    {
        $opportunity = $this->em->getRepository('TruckeeMatchBundle:Opportunity')->findOneBy(['oppName' => 'Feeder']);
        $this->addSearch($opportunity);

        $searches = $opportunity->getSearches();

        $this->assertEquals(2, count($searches));
    }
    
    public function testSearchFocusesSkills()
    {
        $opportunity = $this->em->getRepository('TruckeeMatchBundle:Opportunity')->findOneBy(['oppName' => 'Feeder']);
        $this->addSearch($opportunity);
        $searches  = $this->em->getRepository('TruckeeMatchBundle:Search')->findAll();
        $focuses = [];
        $skills = [];
        foreach($searches as $search) {
            if (!empty($search->getFocus())) {
                $focuses[] = $search->getFocus();
            }
            if (!empty($search->getSkill())) {
                $skills[] = $search->getSkill();
            }
        }
        $this->assertGreaterThan(0, count($focuses));
        $this->assertGreaterThan(0, count($skills));
    }
    
    public function testSearchTypeDate()
    {
        $opportunity = $this->em->getRepository('TruckeeMatchBundle:Opportunity')->findOneBy(['oppName' => 'Feeder']);
        $this->addSearch($opportunity);
        $search = $this->em->getRepository('TruckeeMatchBundle:Search')->find(1);
        
        $this->assertNotNull($search->getDate());
        $this->assertNotNull($search->getType());
    }
}
