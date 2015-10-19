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

use Doctrine\ORM\EntityRepository;

/**
 * Description of OpportunityRepository
 *
 * @author George
 */
class OpportunityRepository extends EntityRepository
{

    /**
     * Return opportunities matching focus and skill criteria
     * or all opportunities for no selections.
     * Search criteria saved for later analysis
     * 
     * @param array $data
     * @return array
     */
    public function doFocusSkillSearch($data)
    {
        $focusesExist = array_key_exists('focuses', $data);
        $skillsExist = array_key_exists('skills', $data);
        $orgExists = array_key_exists('organization', $data);
        
        $em = $this->getEntityManager();
        $conn = $em->getConnection();

        $select = "SELECT o.orgName, o.website, o.background, '' as rank, "
                . "op.id, op.orgId, op.oppName, op.description, op.minAge, op.expireDate "
                . "FROM opportunity op "
                . "JOIN organization o ON o.id = op.orgId "
                . "LEFT OUTER JOIN staff st ON o.id = st.orgID "
                . "LEFT OUTER JOIN person p ON st.id = p.id ";

        $now = date_format(new \DateTime(), 'Y-m-d');

        $criteria = '';
        if ($orgExists && '' !== $data['organization']['organization']) {
            $orgId = $data['organization']['organization'];
            $criteria .= "o.id = $orgId AND ";
        }

        $criteria .= "o.active = '1' AND op.active = '1' "
                . "AND op.expireDate >= '$now' "
                . "AND p.locked = '0' "
                . "ORDER BY o.orgName, op.oppName ";

        $foci = $skills = array();
        if (!$focusesExist && !$skillsExist) {
            $sqlAll = $select . 'WHERE ' . $criteria;
            $stmt = $conn->executeQuery($sqlAll);
            $opportunities = $stmt->fetchAll();
            if (empty($opportunities)) {
                return null;
            }
            foreach ($opportunities as $key => $row) {
                $orgName[$key] = $row['orgName'];
                $oppName[$key] = $row['oppName'];
            }
            array_multisort($orgName, SORT_ASC, $oppName, SORT_ASC, $opportunities);
        }
        else {
            $oppByFocus = array();
            $nFocus = ($focusesExist) ? count($data['focuses']) : 0;
            if ($focusesExist) {
                $foci = $data['focuses'];
                $sqlFocus = $select
                        . "JOIN org_focus of ON o.id = of.orgId "
                        . "JOIN focus f ON f.id = of.focusId "
                        . "WHERE f.id IN (?) AND "
                        . $criteria;
                $stmt = $conn->executeQuery($sqlFocus, array($foci), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
                $oppByFocus = $stmt->fetchAll();
            }
            $oppBySkill = array();
            $nSkill = ($skillsExist) ? count($data['skills']) : 0;
            if ($skillsExist) {
                $skills = $data['skills'];
                $sqlSkill = $select
                        . "JOIN opp_skill os ON op.id = os.oppId "
                        . "JOIN skill s ON s.id = os.skillId "
                        . "WHERE s.id IN (?) AND "
                        . $criteria;
                $stmt = $conn->executeQuery($sqlSkill, array($skills), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
                $oppBySkill = $stmt->fetchAll();
            }

            $opportunities = array_merge($oppByFocus, $oppBySkill);

            if (empty($opportunities)) {
                return null;
            }

            array_unique($opportunities, SORT_REGULAR);

            $max = $nFocus + $nSkill;
            $i = '0';
            foreach ($opportunities as $key => $opp) {
                if ($focusesExist) {
                    $org = $em->getRepository("TruckeeMatchBundle:Organization")->find($opp['orgId']);
                    $focuses = $org->getFocuses();
                    foreach ($focuses as $focus) {
                        if (in_array($focus->getId(), $data['focuses'])) {
                            $i++;
                        }
                    }
                }

                if ($skillsExist) {
                    $opportunity = $em->getRepository("TruckeeMatchBundle:Opportunity")->find($opp['id']);
                    $skills = $opportunity->getSkills();
                    foreach ($skills as $skill) {
                        if (in_array($skill->getId(), $data['skills'])) {
                            $i++;
                        }
                    }
                }
                //rank is percentage of possible criteria met by opportunity
                $opportunities[$key]['rank'] = ($max) ? sprintf("%01.2f", 100 * $i / $max) : '1';
            }
            foreach ($opportunities as $key => $row) {
                $rank[$key] = $row['rank'];
                $orgName[$key] = $row['orgName'];
                $oppName[$key] = $row['oppName'];
            }
            array_multisort($rank, SORT_DESC, $orgName, SORT_ASC, $oppName, SORT_ASC, $opportunities);
        }

        return $opportunities;
    }

    public function noEmails()
    {
        return $this->getEntityManager()
                        ->createQuery(
                                'SELECT p FROM TruckeeMatchBundle:Opportunity p '
                                . 'LEFT JOIN TruckeeMatchBundle:AdminOutbox a '
                                . "with p.id = a.oppId AND a.function = 'showMatchedVolunteersAction' "
                                . "WHERE a.oppId IS NULL"
                        )->getResult();
    }

    /**
     * Get opportunities where AdminOutbox function = expiringAlertsAction and month & year of now()
     * @return type
     */
    public function expiringOppsSent()
    {
        //find oppId in AdminOutbox where function = expiringAlertsAction and month & year of now()
        $month = date_format(new \DateTime(), 'm');
        $year = date_format(new \DateTime(), 'Y');
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('o')
            ->from('TruckeeMatchBundle:Opportunity', 'o')
            ->join('TruckeeMatchBundle:AdminOutbox', 'a', 'WITH', $qb->expr()->eq('a.oppId', 'o'))
            ->andWhere($qb->expr()->eq('month(a.date)', ':month'))
            ->andWhere($qb->expr()->eq('Year(a.date)', ':year'))
            ->andWhere($qb->expr()->eq('a.function', ':function'))
            ->setParameter(':month', $month)
            ->setParameter(':year', $year)
            ->setParameter(':function', 'expiringAlertsAction')
        ;
        $sent = $qb->getQuery()->getResult();

        return $sent;
    }

    /**
     * Get opportunites expiring next month where there are no related entries in AdminOutbox
     */
    public function expiringOppsNotSent()
    {
        $nextMonth = date_add(new \DateTime(), new \DateInterval('P1M'));
        $expiryMonth = date_format($nextMonth, 'm');
        $expiryYear = date_format($nextMonth, 'Y');
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('o')
            ->from('TruckeeMatchBundle:Opportunity', 'o')
            ->leftJoin('TruckeeMatchBundle:AdminOutbox', 'a', 'WITH', $qb->expr()->eq('a.oppId', 'o'))
            ->andWhere($qb->expr()->eq('month(o.expireDate)', ':month'))
            ->andWhere($qb->expr()->eq('Year(o.expireDate)', ':year'))
            ->andWhere('a.id is NULL')
            ->setParameter(':month', $expiryMonth)
            ->setParameter(':year', $expiryYear)
            ;
        $notSent = $qb->getQuery()->getResult();

        return $notSent;
    }
}
