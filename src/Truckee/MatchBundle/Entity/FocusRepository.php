<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Entity\FocusRepository.php

namespace Truckee\MatchBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FocusRepository extends EntityRepository
{
    public function getFocusesNoAll()
    {
        $em = $this->getEntityManager();

        return $em->createQuery('select f from TruckeeMatchBundle:Focus f '
                ."WHERE f.focus <> 'All' "
                .'order by f.focus asc')->getResult();
    }

    public function countFocuses()
    {
        $em = $this->getEntityManager();

        return $em->createQuery('select count(f) from TruckeeMatchBundle:Focus f '
                    ."WHERE f.enabled = '1'")
                ->getSingleScalarResult();
    }
}
