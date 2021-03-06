<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Entity\VolunteerRepository.php

namespace Truckee\MatchBundle\Entity;

use Doctrine\ORM\EntityRepository;

class VolunteerRepository extends EntityRepository
{

    public function getVolunteersByFocus($focuses)
    {
        $conn = $this->getEntityManager()->getConnection();
        $volByFocus = array();
        if (!empty($focuses)) {
            $sqlFocus = 'SELECT v.id FROM volunteer v '
                . 'JOIN person p on v.id = p.id '
                . 'JOIN vol_focus vf ON v.id = vf.volId '
                . 'JOIN focus f ON f.id = vf.focusId '
                . "WHERE v.receive_email = '1' AND p.enabled = '1' AND f.id IN (?)";
            $stmt = $conn->executeQuery($sqlFocus, array($focuses), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
            $volByFocus = $stmt->fetchAll();
        }

        return $volByFocus;
    }

    public function getVolunteersBySkill($skills)
    {
        $conn = $this->getEntityManager()->getConnection();
        if (!empty($skills)) {
            $sqlSkill = 'SELECT v.id FROM volunteer v '
                . 'JOIN person p on v.id = p.id '
                . 'JOIN vol_skill vs ON v.id = vs.volId '
                . 'JOIN skill s ON s.id = vs.skillId '
                . "WHERE v.receive_email = '1' AND p.enabled = '1' AND s.id IN (?)";
            $stmt = $conn->executeQuery($sqlSkill, array($skills), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
            $volBySkill = $stmt->fetchAll();
        }

        return $volBySkill;
    }
}
