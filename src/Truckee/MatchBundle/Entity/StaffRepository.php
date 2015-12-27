<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Entity\StaffRepository.php

namespace Truckee\MatchBundle\Entity;

use Doctrine\ORM\EntityRepository;

class StaffRepository extends EntityRepository
{
    public function getActivePersons($id)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->select('s')
                        ->join('s.organization', 'o')
                        ->where("o.id = $id")
                        ->andWhere('s.locked = false')
                        ->getQuery()->getResult();
    }
}
