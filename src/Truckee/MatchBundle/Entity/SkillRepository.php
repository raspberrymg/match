<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Entity\SkillRepository.php

namespace Truckee\MatchBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SkillRepository extends EntityRepository
{

    public function getSkillsNoAll()
    {
        $em = $this->getEntityManager();

        return $em->createQuery('select s from TruckeeMatchBundle:Skill s '
                . "WHERE s.skill <> 'All' "
                . 'order by s.skill asc')->getResult();
    }

    public function countSkills()
    {
        $em = $this->getEntityManager();

        return $em->createQuery('select count(s) from TruckeeMatchBundle:Skill s '
                    . "WHERE s.enabled = '1'")
                ->getSingleScalarResult();
    }
}
