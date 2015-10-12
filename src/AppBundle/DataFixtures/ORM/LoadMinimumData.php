<?php
/*
 * This file is part of the App package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\DataFixtures\ORM\LoadMinimumData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * LoadMinimumData: minimum data for application
 *
 * @author George Brooks <truckeesolutions@gmail.com>
 */
class LoadMinimumData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        //create admin user

        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('AppBundle\Entity\Admin');

        $userManager = $this->container->get('pugx_user_manager');

        $admin = $userManager->createUser();

        $userName  = $this->container->getParameter('admin_username');
        $email     = $this->container->getParameter('admin_email');
        $password  = $this->container->getParameter('admin_password');
        $firstName = $this->container->getParameter('admin_first_name');
        $lastName  = $this->container->getParameter('admin_last_name');

        $admin->setUsername($userName);
        $admin->setEmail($email);
        $admin->setPlainPassword($password);
        $admin->setEnabled(true);
        $admin->setFirstName($firstName);
        $admin->setLastName($lastName);
        $admin->addRole('ROLE_SUPER_ADMIN');

        $userManager->updateUser($admin, true);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
