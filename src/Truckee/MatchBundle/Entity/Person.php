<?php

/*
 * This file is part of the Truckee\Match package.
 *
 * (c) George W. Brooks
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Entity\Person.php


namespace Truckee\MatchBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="person")
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"staff" = "Staff", "volunteer" = "Volunteer", "admin"="Admin"})
 */
abstract class Person extends BaseUser
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->events = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message = "First name is required")
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message = "Last name is required")
     */
    protected $lastName;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Truckee\MatchBundle\Entity\Event", mappedBy="owner", cascade={"persist","remove"})
     */
    protected $events;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="add_date", type="datetime", nullable=true)
     */
    protected $addDate;

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
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    public function getName()
    {
        return $this->getLastName().', '.$this->getFirstName();
    }
    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * Add events.
     *
     * @param \Truckee\MatchBundle\Entity\Event $events
     *
     * @return Event
     */
    public function addEvent(\Truckee\MatchBundle\Entity\Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove events.
     *
     * @param \Truckee\MatchBundle\Entity\Event $events
     */
    public function removeEvent(\Truckee\MatchBundle\Entity\Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function getNameLockStatus()
    {
        $marker = ($this->locked) ? '*' : '';

        return $this->getLastName().', '.$this->getFirstName().$marker;
    }

    /**
     * Set addDate.
     *
     * @param \DateTime $addDate
     *
     * @return Person
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;

        return $this;
    }

    /**
     * Get addDate.
     *
     * @return \DateTime
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    public function getUserType()
    {
        return $this->discr;
    }

    public function changeLockState()
    {
        $state = $this->isLocked();
        $this->locked = !$state;
    }
}
