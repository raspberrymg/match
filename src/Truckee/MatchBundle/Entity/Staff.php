<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Truckee\MatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * Staff.
 *
 * @ORM\Table(name="staff")
 * @ORM\Entity(repositoryClass="Truckee\MatchBundle\Entity\StaffRepository")
 * @UniqueEntity(fields = "username", targetClass = "Truckee\MatchBundle\Entity\Person", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "Truckee\MatchBundle\Entity\Person", message="fos_user.email.already_used")
 */
class Staff extends Person
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Truckee\MatchBundle\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Truckee\MatchBundle\Entity\Organization", inversedBy="staff", cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="orgId", referencedColumnName="id")
     * })
     */
    protected $organization;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set organization.
     *
     * @param \Truckee\MatchBundle\Entity\Organization $organization
     *
     * @return Staff
     */
    public function setOrganization(\Truckee\MatchBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization.
     *
     * @return \Truckee\MatchBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
