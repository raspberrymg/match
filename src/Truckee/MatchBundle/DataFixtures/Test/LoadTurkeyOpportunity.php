<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license infTestation, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\DataFixtures\Test\LoadTurkeyOpportunity

namespace Truckee\MatchBundle\DataFixtures\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Truckee\MatchBundle\Entity\Opportunity;

/**
 * Loads opportunity data.
 */
class LoadTurkeyOpportunity extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load fixtures.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->clear();
        $org = $manager->getRepository('TruckeeMatchBundle:Organization')->findOneBy(array('orgName' => 'Turkeys R Us'));
        $skill = $manager->getRepository('TruckeeMatchBundle:Skill')->findOneBy(array('skill' => 'Administrative Support'));
        $opp = new Opportunity();
        $opp->setOppName('Defeatherer');
        $opp->setDescription('Take the fuzzy stuff off!');
        $opp->setActive(true);
        $opp->setExpireDate(date_add(new \DateTime(), new \DateInterval('P1M')));
        $opp->setOrganization($org);
        $opp->addSkill($skill);
        $opp->setAddDate(new \DateTime());
        $opp->setLastupdate(new \DateTime());
        $manager->persist($opp);
        $manager->flush();
    }

    public function getOrder()
    {
        return 9; // the order in which fixtures will be loaded
    }
}
