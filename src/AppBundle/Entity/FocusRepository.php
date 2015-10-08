<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\FocusRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of FocusRepository
 *
 * @author George
 */
class FocusRepository extends EntityRepository
{
    
    public function getFocusesNoAll()
    {
        $em  = $this->getEntityManager();
        return $em->createQuery("select f from AppBundle:Focus f "
                . "WHERE f.focus <> 'All' "
                . "order by f.focus asc")->getResult();
    }

    public function isFocusPopulated()
    {
        $em  = $this->getEntityManager();
        return $em->createQuery("select count(f) from AppBundle:Focus f "
            . "WHERE f.enabled = '1'")
            ->getSingleScalarResult();
    }
}
