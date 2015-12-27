<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Entity\Admin.php

namespace Truckee\MatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * Admin.
 *
 * @ORM\Table(name="admin")
 * @ORM\Entity
 * @UniqueEntity(fields = "username", targetClass = "Truckee\MatchBundle\Entity\Person", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "Truckee\MatchBundle\Entity\Person", message="fos_user.email.already_used")
 */
class Admin extends Person
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    protected $discr = 'admin';
}
