<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\DataFixtures\ORM\LoadInitialFocusSkill.php


namespace Truckee\MatchBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Truckee\MatchBundle\Entity\Focus;
use Truckee\MatchBundle\Entity\Skill;

/**
 * LoadInitialFocusSkill.
 */
class LoadInitialFocusSkill implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->clear();
        $focus = new Focus();
        $focus->setFocus('All');
        $focus->setEnabled(true);
        $manager->persist($focus);

        $skill = new Skill();
        $skill->setSkill('All');
        $skill->setEnabled(true);
        $manager->persist($skill);

        $manager->flush();
    }
}
