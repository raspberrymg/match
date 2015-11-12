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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Opportunity.
 *
 * @ORM\Table(name="volunteer")
 * @ORM\Entity(repositoryClass="Truckee\MatchBundle\Entity\VolunteerRepository")
 * @UniqueEntity(fields = "username", targetClass = "Truckee\MatchBundle\Entity\Person", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "Truckee\MatchBundle\Entity\Person", message="fos_user.email.already_used")
 */
class Volunteer extends Person
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    protected $discr = 'volunteer';

    /**
     * @var int
     *
     * @ORM\Column(name="receive_email", type="boolean", nullable=true)
     */
    protected $receiveEmail;

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
     * Set receiveEmail.
     *
     * @param string $receiveEmail
     *
     * @return Person
     */
    public function setReceiveEmail($receiveEmail)
    {
        $this->receiveEmail = $receiveEmail;

        return $this;
    }

    /**
     * Get receiveEmail.
     *
     * @return string
     */
    public function getReceiveEmail()
    {
        return $this->receiveEmail;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Focus", inversedBy="volunteers", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="vol_focus",
     *      joinColumns={@ORM\JoinColumn(name="volId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="focusId", referencedColumnName="id")}
     *      ))
     * @Assert\NotNull(message="Please select at least one", groups={"focus_required"})
     */
    protected $focuses;

    /**
     * Add focuses.
     *
     * @param \Truckee\MatchBundle\Entity\Focus $focuses
     *
     * @return Opportunity
     */
    public function addFocus(\Truckee\MatchBundle\Entity\Focus $focus)
    {
        $this->focuses[] = $focus;

        return $this;
    }

    /**
     * Remove focuses.
     *
     * @param \Truckee\MatchBundle\Entity\Focus $focuses
     */
    public function removeFocus(\Truckee\MatchBundle\Entity\Focus $focus)
    {
        $this->focuses->removeElement($focus);
    }

    /**
     * Get focuses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFocuses()
    {
        return $this->focuses;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Skill", inversedBy="volunteers", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="vol_skill",
     *      joinColumns={@ORM\JoinColumn(name="volId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="skillId", referencedColumnName="id")}
     *      ))
     * @Assert\NotNull(message="Please select at least one", groups={"skill_required"})
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
}
