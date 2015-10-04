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

use Doctrine\ORM\EntityRepository;

/**
 * Description of VolunteerRepository
 *
 * @author George
 */
class VolunteerRepository extends EntityRepository
{
    /**
     * Get id array of volunteers with at least one of requested focus or skill
     * 
     * @param type $focuses
     * @param type $skills
     * @return array
     */
    public function getVolunteerVolunteers($focuses, $skills)
    {
        $conn = $this->getEntityManager()->getConnection();
        $volByFocus = array();
        $volBySkill = array();

        if (!empty($focuses)) {
            $sqlFocus = "SELECT v.* FROM volunteer v "
                    . "JOIN person p on v.id = p.id "
                    . "JOIN vol_focus vf ON v.id = vf.volId "
                    . "JOIN focus f ON f.id = vf.focusId "
                    . "WHERE v.receive_email = '1' AND p.enabled = '1' AND f.id IN (?)";
            $stmt = $conn->executeQuery($sqlFocus, array($focuses), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
            $volByFocus = $stmt->fetchAll();
        }
        if (!empty($skills)) {
            $sqlSkill = "SELECT v.* FROM volunteer v "
                    . "JOIN person p on v.id = p.id "
                    . "JOIN vol_skill vs ON v.id = vs.volId "
                    . "JOIN skill s ON s.id = vs.skillId "
                    . "WHERE v.receive_email = '1' AND p.enabled = '1' AND s.id IN (?)";
            $stmt = $conn->executeQuery($sqlSkill, array($skills), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
            $volBySkill = $stmt->fetchAll();
        }
        
        $finalArray = array_unique(array_merge($volByFocus, $volBySkill), SORT_REGULAR);
        return $finalArray;
    }
}
