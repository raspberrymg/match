<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Search
 *
 * @ORM\Table("search")
 * @ORM\Entity
 */
class Search
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Search
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @var \AppBundle\Entity\Focus
     *
     * @ORM\ManyToOne(targetEntity="Focus", inversedBy="searches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="focus_id", referencedColumnName="id")
     * })
     */
    protected $focus;

    /**
     * Set focus
     *
     * @param \AppBundle\Entity\Focus $focus
     * @return Contact
     */
    public function setFocus(Focus $focus = null)
    {
        $this->focus = $focus;

        return $this;
    }

    /**
     * Get focus
     *
     */
    public function getFocus()
    {
        return $this->focus;
    }

    /**
     * @var \AppBundle\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Organization", inversedBy="searches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="org_id", referencedColumnName="id")
     * })
     */
    protected $organization;

    /**
     * Set organization
     *
     * @param \AppBundle\Entity\Organization $organization
     * @return Contact
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @var \AppBundle\Entity\Opportunity
     *
     * @ORM\ManyToOne(targetEntity="Opportunity", inversedBy="searches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="opp_id", referencedColumnName="id")
     * })
     */
    protected $opportunity;

    /**
     * Set opportunity
     *
     * @param \AppBundle\Entity\Opportunity $opportunity
     * @return Contact
     */
    public function setOpportunity(Opportunity $opportunity = null)
    {
        $this->opportunity = $opportunity;

        return $this;
    }

    /**
     * Get opportunity
     *
     */
    public function getOpportunity()
    {
        return $this->opportunity;
    }

    /**
     * @var \AppBundle\Entity\Skill
     *
     * @ORM\ManyToOne(targetEntity="Skill", inversedBy="searches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="skill_id", referencedColumnName="id")
     * })
     */
    protected $skill;

    /**
     * Set skill
     *
     * @param \AppBundle\Entity\Skill $skill
     * @return Contact
     */
    public function setSkill(Skill $skill = null)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     *
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return Searcn
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return integer 
     */
    public function getDate()
    {
        return $this->date;
    }
}
