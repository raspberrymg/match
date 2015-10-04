<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\Event.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Event
 * @ORM\Table(name="event")
 * @ORM\Entity
 * @author George
 */
class Event
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="event", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "Event description is required")
     */
    private $event;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eventDate", type="date", nullable=true)
     * @Assert\Date()
     * @Assert\NotBlank(message = "Event date is required")
     */
    private $eventdate;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=45, nullable=true)
     * @Assert\NotBlank(message = "Event location is required")
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="starttime", type="string", length=10, nullable=true)
     * @Assert\NotBlank(message = "Event start time is required")
     */
    private $starttime;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="events", cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="personId", referencedColumnName="id")
     * })
     */
    private $owner;
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Opportunity
     */
    public function setEvent($name)
    {
        $this->event = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set eventdate
     *
     * @param \DateTime $eventdate
     * @return Opportunity
     */
    public function setEventdate($eventdate)
    {
        $this->eventdate = $eventdate;

        return $this;
    }

    /**
     * Get eventdate
     *
     * @return \DateTime 
     */
    public function getEventdate()
    {
        return $this->eventdate;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Opportunity
     */
    public function setLocation($name)
    {
        $this->location = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     *
     * @param string $starttime
     */
    public function setStarttime($time)
    {
        $this->starttime = $time;
    
        return $this;
    }

    /**
     * Get starttime
     *
     * @return string 
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\Person $owner
     */
    public function setOwner(\AppBundle\Entity\Person $owner = null)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\Person 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
