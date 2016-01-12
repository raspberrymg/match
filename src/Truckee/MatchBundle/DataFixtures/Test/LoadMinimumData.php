<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license infTestation, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\DataFixtures\Test\LoadMinimumData.php

namespace Truckee\MatchBundle\DataFixtures\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Truckee\MatchBundle\Entity\Focus;
use Truckee\MatchBundle\Entity\Skill;

/**
 * LoadMinimumData: minimum data for application.
 * Brooks <truckeesolutions@gmail.com>
 */
class LoadMinimumData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('Truckee\MatchBundle\Entity\Admin');

        $userManager = $this->container->get('pugx_user_manager');

        $admin = $userManager->createUser();

        $userName = $this->container->getParameter('admin_username');
        $email = $this->container->getParameter('admin_email');
        $password = $this->container->getParameter('admin_password');
        $firstName = $this->container->getParameter('admin_first_name');
        $lastName = $this->container->getParameter('admin_last_name');

        $admin->setUsername($userName);
        $admin->setEmail($email);
        $admin->setPlainPassword($password);
        $admin->setEnabled(true);
        $admin->setFirstName($firstName);
        $admin->setLastName($lastName);
        $admin->addRole('ROLE_SUPER_ADMIN');

        $userManager->updateUser($admin, true);
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
