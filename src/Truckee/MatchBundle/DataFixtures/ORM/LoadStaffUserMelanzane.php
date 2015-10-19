<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\DataFixtures\ORM\LoadStaffUserGlenshire

namespace Truckee\MatchBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Truckee\MatchBundle\Entity\Organization;

/**
 * Loads user data
 */
class LoadStaffUserMelanzane extends AbstractFixture implements  ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load fixtures
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->clear();

        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('Truckee\MatchBundle\Entity\Staff');

        $userManager = $this->container->get('pugx_user_manager');

        $staff = $userManager->createUser();

        $staff->setUsername('jmelanzane');
        $staff->setEmail('jmelanzane@bogus.info');
        $staff->setPlainPassword('123Abcd');
        $staff->setEnabled(true);
        $staff->setFirstName('Joe');
        $staff->setLastName('Melanzane');
        $staff->addRole('ROLE_STAFF');
        $org = new Organization();
        $org->setOrgName('Glenshire Marmite Fund');
        $org->setAddress('PO Box 999');
        $org->setCity('Truckee');
        $org->setState('CA');
        $org->setZip('96160');
        $org->setWebsite('www.melanzanemarmots.org');
        $org->setEmail('jmelanzane@bogus.info');
        $org->setTemp(true);
        $foc1 = $manager->getRepository("TruckeeMatchBundle:Focus")->findOneByFocus('Seniors');
        $org->addFocus($foc1);
        $manager->persist($org);
        $staff->setOrganization($org);

        $manager->flush();

        $userManager->updateUser($staff, true);
    }

    public function getOrder()
    {
        return 7; // the order in which fixtures will be loaded
    }
}
