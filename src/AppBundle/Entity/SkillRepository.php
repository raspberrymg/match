<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\SkillRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of SkillRepository
 *
 * @author George
 */
class SkillRepository extends EntityRepository
{

    public function getSkillsNoAll()
    {
        $em  = $this->getEntityManager();
        return $em->createQuery("select s from AppBundle:Skill s "
                . "WHERE s.skill <> 'All' "
                . "order by s.skill asc")->getResult();
    }
}
