<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license infTestation, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\DataFixtures\Test\LoadStaffUserBorko

namespace Truckee\MatchBundle\DataFixtures\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Truckee\MatchBundle\Entity\Organization;

/**
 * Loads user data
 */
class LoadStaffUserBorko extends AbstractFixture implements  ContainerAwareInterface, OrderedFixtureInterface
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

        $staff->setUsername('bborko');
        $staff->setEmail('bborko@bogus.info');
        $staff->setPlainPassword('123Abcd');
        $staff->setEnabled(true);
        $staff->setFirstName('Benny');
        $staff->setLastName('Borko');
        $staff->addRole('ROLE_STAFF');
        $org = new Organization();
        $org->setOrgName('Turkeys R Us');
        $org->setAddress('PO Box 234');
        $org->setCity('Truckee');
        $org->setState('CA');
        $org->setZip('96160');
        $org->setWebsite('www.turkeys.org');
        $org->setEmail('turkeys@bogus.info');
        $org->setTemp(false);
        $org->setAddDate(new \DateTime());
        $foc1 = $manager->getRepository("TruckeeMatchBundle:Focus")->findOneByFocus('Animal Welfare');
        $org->addFocus($foc1);
        $manager->persist($org);
        $staff->setOrganization($org);

        $manager->flush();

        $userManager->updateUser($staff, true);
    }

    public function getOrder()
    {
        return 8; // the order in which fixtures will be loaded
    }
}
