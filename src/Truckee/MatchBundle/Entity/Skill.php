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

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Skill.
 *
 * @ORM\Table(name="skill")
 * @ORM\Entity(repositoryClass="Truckee\MatchBundle\Entity\SkillRepository")
 * @UniqueEntity("skill", message="Skill has already been used")
 */
class Skill
{
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
     * @ORM\Column(name="skill", type="string", length=45, nullable=true)
     */
    private $skill;

    /**
     * @var string
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

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
     * Set skill.
     *
     * @param string $skill
     *
     * @return Skill
     */
    public function setSkill($skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill.
     *
     * @return string
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Set enabled.
     *
     * @param string $enabled
     *
     * @return Enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return string
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Opportunity", mappedBy="skills")
     */
    protected $opportunities;

//    public function addOpportunity(Opportunity $opportunity) {
//        $this->opportunities[] = $opportunity;
//    }
//
//    public function getOpportunities() {
//        return $this->opportunities;
//    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Volunteer", mappedBy="skills")
     */
    protected $volunteers;

//    public function addVolunteer(Volunteer $volunteer) {
//        $this->volunteers[] = $volunteer;
//    }
//
//    public function getVolunteers() {
//        return $this->volunteers;
//    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Search", mappedBy="skill", cascade={"persist"})
     */
    protected $searches;

//    /**
//     * Add searches
//     *
//     * @param Search $searches
//     * @return Household
//     */
//    public function addSearch(Search $search) {
//        $this->searches[] = $search;
//        $search->Skill($this);
//        return $this;
//    }
//
//    /**
//     * Remove searches
//     *
//     * @param Search $searches
//     */
//    public function removeSearch(Search $search) {
//        $this->searches->removeElement($search);
//    }
//
//    /**
//     * Get searches
//     *
//     * @return \Doctrine\Common\Collections\Collection 
//     */
//    public function getSearches() {
//        return $this->searches;
//    }
}
