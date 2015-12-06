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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Opportunity.
 *
 * @ORM\Table(name="opportunity")
 * @ORM\Entity(repositoryClass="Truckee\MatchBundle\Entity\OpportunityRepository")
 */
class Opportunity
{
    public function __construct()
    {
        $this->volunteers = new ArrayCollection();
        $this->email = new ArrayCollection();
        $this->searches = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="oppName", type="string", length=66, nullable=true)
     * @Assert\NotBlank(message = "Opportunity name is required")
     */
    private $oppName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="add_date", type="date", nullable=true)
     */
    private $addDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
     */
    private $lastupdate;

    /**
     * @var int
     *
     * @ORM\Column(name="minAge", type="text", nullable=true)
     */
    private $minage;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var bool
     *
     * @ORM\Column(name="group_ok", type="boolean", nullable=true)
     */
    private $groupOk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expireDate", type="date", nullable=true)
     * @Assert\Date()
     */
    private $expireDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\NotBlank(message="Description is required")
     */
    private $description;

    /**
     * @var \Truckee\MatchBundle\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Truckee\MatchBundle\Entity\Organization", inversedBy="opportunities", cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="orgId", referencedColumnName="id")
     * })
     */
    private $organization;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Truckee\MatchBundle\Entity\Search", mappedBy="opportunity", cascade={"persist","remove","merge"})
     */
    protected $searches;

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
     * Set name.
     *
     * @param string $name
     *
     * @return Opportunity
     */
    public function setOppName($name)
    {
        $this->oppName = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getOppName()
    {
        return $this->oppName;
    }

    /**
     * Set addDate.
     *
     * @param \DateTime $addDate
     *
     * @return Opportunity
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

    /**
     * Set lastupdate.
     *
     * @param \DateTime $lastupdate
     *
     * @return Opportunity
     */
    public function setLastupdate($lastupdate)
    {
        $this->lastupdate = $lastupdate;

        return $this;
    }

    /**
     * Get lastupdate.
     *
     * @return \DateTime
     */
    public function getLastupdate()
    {
        return $this->lastupdate;
    }

    /**
     * Set minage.
     *
     * @param int $minage
     *
     * @return Opportunity
     */
    public function setMinage($minage)
    {
        $this->minage = $minage;

        return $this;
    }

    /**
     * Get minage.
     *
     * @return int
     */
    public function getMinage()
    {
        return $this->minage;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Opportunity
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set group.
     *
     * @param bool $groupOk
     *
     * @return Opportunity
     */
    public function setGroupOk($groupOk)
    {
        $this->groupOk = $groupOk;

        return $this;
    }

    /**
     * Get group.
     *
     * @return bool
     */
    public function getGroupOk()
    {
        return $this->groupOk;
    }

    /**
     * Set expiredate.
     *
     * @param \DateTime $expiredate
     *
     * @return Opportunity
     */
    public function setExpireDate($expiredate)
    {
        $this->expireDate = $expiredate;

        return $this;
    }

    /**
     * Get expiredate.
     *
     * @return \DateTime
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Opportunity
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set organization.
     *
     * @param \Truckee\MatchBundle\Entity\Organization $organization
     *
     * @return Opportunity
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

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Skill", inversedBy="opportunities", cascade={"persist"})
     * @ORM\JoinTable(name="opp_skill",
     *      joinColumns={@ORM\JoinColumn(name="oppId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="skillId", referencedColumnName="id")}
     *      ))
     */
    protected $skills;

    /**
     * Add skills.
     *
     * @param \Truckee\MatchBundle\Entity\Skill $skills
     *
     * @return Opportunity
     */
    public function addSkill(\Truckee\MatchBundle\Entity\Skill $skill)
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * Remove skills.
     *
     * @param \Truckee\MatchBundle\Entity\Skill $skills
     */
    public function removeSkill(\Truckee\MatchBundle\Entity\Skill $skill)
    {
        $this->skills->removeElement($skill);
    }

    /**
     * Get skills.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Get search.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSearches()
    {
        return $this->searches;
    }
}
