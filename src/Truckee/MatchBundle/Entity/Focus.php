<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Entity\Focus.php

namespace Truckee\MatchBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Focus.
 *
 * @ORM\Table(name="focus")
 * @ORM\Entity(repositoryClass="Truckee\MatchBundle\Entity\FocusRepository")
 * @UniqueEntity("focus")
 */
class Focus
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
     * @var string
     *
     * @ORM\Column(name="focus", type="string", length=45, nullable=true)
     */
    protected $focus;

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
     * Set focus.
     *
     * @param string $focus
     *
     * @return Focus
     */
    public function setFocus($focus)
    {
        $this->focus = $focus;

        return $this;
    }

    /**
     * Get focus.
     *
     * @return string
     */
    public function getFocus()
    {
        return $this->focus;
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
     * @ORM\ManyToMany(targetEntity="Organization", mappedBy="focuses")
     */
    protected $organizations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Volunteer", mappedBy="focuses")
     */
    protected $volunteers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Search", mappedBy="focus", cascade={"persist"})
     */
    protected $searches;

}
