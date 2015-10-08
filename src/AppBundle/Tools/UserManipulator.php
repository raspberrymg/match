<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Tools;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\UserManipulator as Manipulator;

/**
 * Executes some manipulations on the users
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class UserManipulator extends Manipulator
{

    /**
     * User manager
     *
     * @var UserManagerInterface
     */
    private $userManager;
    private $discriminator;

    public function __construct(UserManagerInterface $userManager, $discriminator)
    {
        $this->userManager = $userManager;
        $this->discriminator = $discriminator;
    }

    /**
     * Creates a user and returns it.
     *
     * @param string  $username
     * @param string  $password
     * @param string  $email
     * @param Boolean $active
     * @param Boolean $superadmin
     *
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function create($username, $password, $email, $active, $superadmin)
    {
        $discriminator = $this->discriminator;

        switch ($this->type) {
            case 'staff':
                $class = 'AppBundle\Entity\Staff';
                break;
            case 'admin':
                $class = 'AppBundle\Entity\Admin';
                break;
            case 'volunteer':
                $class = 'AppBundle\Entity\Volunteer';
                break;
            default:
                break;
        }

        $discriminator->setClass($class);

        $user = $this->userManager->createUser();
        $user->setUsername($username);
        $user->setFirstname($this->firstname);
        $user->setLastname($this->lastname);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled((Boolean) $active);
        $this->userManager->updateUser($user, true);

        return $user;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
    
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
}
