<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license infTestation, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Repository\DataFixtures\Test\LoadVolunteer


namespace Truckee\MatchBundle\DataFixtures\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Loads user data.
 */
class LoadVolunteer extends AbstractFixture implements  ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load fixtures.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('Truckee\MatchBundle\Entity\Volunteer');

        $userManager = $this->container->get('pugx_user_manager');

        $volunteer = $userManager->createUser();

        $volunteer->setUsername('hvolunteer');
        $volunteer->setEmail('hvolunteer@bogus.info');
        $volunteer->setPlainPassword('123Abcd');
        $volunteer->setEnabled(true);
        $volunteer->setFirstName('Harry');
        $volunteer->setLastName('Volunteer');
        $volunteer->setReceiveEmail(true);
        $volunteer->addRole('ROLE_USER');
        $foc1 = $manager->getRepository('TruckeeMatchBundle:Focus')->findOneByFocus('Animal Welfare');
        $volunteer->addFocus($foc1);
        $skill = $manager->getRepository('TruckeeMatchBundle:Skill')->findOneBy(array('skill' => 'Administrative Support'));
        $volunteer->addSkill($skill);

        $userManager->updateUser($volunteer, true);

        $volunteerA = $userManager->createUser();
        $volunteerA->setUsername('hvola');
        $volunteerA->setEmail('hvola.info');
        $volunteerA->setPlainPassword('123Abcd');
        $volunteerA->setEnabled(true);
        $volunteerA->setFirstName('Harry');
        $volunteerA->setLastName('Volunteer');
        $volunteerA->setReceiveEmail(true);
        $volunteerA->addRole('ROLE_USER');
        $foc1 = $manager->getRepository('TruckeeMatchBundle:Focus')->findOneByFocus('Arts and Culture');
        $volunteerA->addFocus($foc1);
        $skill = $manager->getRepository('TruckeeMatchBundle:Skill')->findOneBy(array('skill' => 'Legal Services'));
        $volunteerA->addSkill($skill);

        $userManager->updateUser($volunteerA, true);

        $volunteerB = $userManager->createUser();
        $volunteerB->setUsername('hvolb');
        $volunteerB->setEmail('hvolb@bogus.info');
        $volunteerB->setPlainPassword('123Abcd');
        $volunteerB->setEnabled(true);
        $volunteerB->setFirstName('Harry');
        $volunteerB->setLastName('Volunteer');
        $volunteerB->setReceiveEmail(true);
        $volunteerB->addRole('ROLE_USER');
        $foc1 = $manager->getRepository('TruckeeMatchBundle:Focus')->findOneByFocus('Animal Welfare');
        $volunteerB->addFocus($foc1);
        $skill = $manager->getRepository('TruckeeMatchBundle:Skill')->findOneBy(array('skill' => 'Administrative Support'));
        $volunteerB->addSkill($skill);

        $userManager->updateUser($volunteerB, true);
    }

    public function getOrder()
    {
        return 6; // the order in which fixtures will be loaded
    }
}
