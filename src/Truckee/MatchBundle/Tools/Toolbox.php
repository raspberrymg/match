<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src/Truckee/MatchBundle/Tools/Toolbox.php

namespace Truckee\MatchBundle\Tools;

use Doctrine\ORM\EntityManager;
use Truckee\MatchBundle\Entity\Search;

/**
 * a set of tools requiring entity manager
 */
class Toolbox
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * gets type as class of user entity
     * e.g., "...\Entity\Admin" is type "admin"
     * 
     * @param object Entity $user
     * @return mixed
     */
    public function getUserType($user)
    {
        if (null === $user || !is_object($user)) {
            return null;
        }

        $class = get_class($user);
        $l = strrpos($class, "\\");
        $type = strtolower(substr($class, $l + 1));
        
        return $type;
    }

    public function getTypeFromId($id)
    {
        $sql = "select A.type from 
            (select 'staff' as type from Staff  s where s.id = :id
            union all
            select 'volunteer' as type from Volunteer v where v.id = :id
            union all
            select 'sandbox' as type from Sandbox b where b.id = :id
            union all
            select 'admin' as type from Admin a where a.id = :id) A";
        
        $conn = $this->em->getConnection();
        $sth = $conn->prepare($sql);
        $sth->bindParam(':id', $id);
        $sth->execute();
        $type = $sth->fetchColumn();

        return  $type;        
    }

    public function setSearchRecord($data, $searched)
    {
        $search = new Search();
        $search->setDate(new \DateTime());
        $search->setType($searched);

        if (array_key_exists('organization', $data) && '' <> $data['organization']['organization']) {
            $orgId = $data['organization']['organization'];
            $organization = $this->em->getRepository("TruckeeMatchBundle:Organization")->find($orgId);
            $search->setOrganization($organization);
        }
        if (array_key_exists('opportunity', $data)) {
            $search->setOpportunity($data['opportunity']);
        }
        
        if (array_key_exists('focuses', $data)) {
            foreach ($data['focuses'] as $focusId) {
                $searchClone = clone $search;
                $focus = $this->em->getRepository("TruckeeMatchBundle:Focus")->find($focusId);
                $searchClone->setFocus($focus);
                $this->em->persist($searchClone);
            }
        }
        if (array_key_exists('skills', $data)) {
            foreach ($data['skills'] as $skillId) {
                $skill = $this->em->getRepository("TruckeeMatchBundle:Skill")->find($skillId);
                $searchClone = clone $search;
                $searchClone->setSkill($skill);
                $searchClone->setType($searched);
                $this->em->persist($searchClone);
            }
        }

        if (!isset($searchClone)) {
            $this->em->persist($search);
        }
        $this->em->flush();
    }

    public function usageFocusSkill()
    {
        $fociUsage['opportunity'] = $skillsUsage['opportunity'] = array();
        $fociUsage['volunteer'] = $skillsUsage['volunteer'] = array();

        $focuses = $this->em->getRepository("TruckeeMatchBundle:Focus")->findAll();
        foreach ($focuses as $focus) {
            $id = $focus->getId();
            $searchOpps = $this->em->getRepository("TruckeeMatchBundle:Search")->findBy(['focus' => $focus, 'type' => 'opportunity']);
            $fociUsage['opportunity'][$id] = count($searchOpps);
            $searchVols = $this->em->getRepository("TruckeeMatchBundle:Search")->findBy(['focus' => $focus, 'type' => 'volunteer']);
            $fociUsage['volunteer'][$id] = count($searchVols);
        }

        $skills = $this->em->getRepository("TruckeeMatchBundle:Skill")->findAll();
        foreach ($skills as $skill) {
            $id = $skill->getId();
            $searchOpps = $this->em->getRepository("TruckeeMatchBundle:Search")->findBy(['skill' => $skill, 'type' => 'opportunity']);
            $skillsUsage['opportunity'][$id] = count($searchOpps);
            $searchVols = $this->em->getRepository("TruckeeMatchBundle:Search")->findBy(['skill' => $skill, 'type' => 'volunteer']);
            $skillsUsage['volunteer'][$id] = count($searchVols);
        }
        return array('fociUsage' => $fociUsage,
            'skillsUsage' => $skillsUsage,
        );
    }

    public function getOrgNames($name)
    {
        $query = $this->em->createQuery("
            SELECT o.id, o.orgName FROM TruckeeMatchBundle:Organization o
            WHERE soundex(o.orgName) LIKE soundex('$name')  AND 
                o.temp = '0'
            ");
        $names = $query->getResult();
        $r = array();
        foreach ($names as $name) {
            $r[$name['id']] = $name['orgName'];
        }

        return $r;
    }

    public function getIncomingOrgs()
    {
        //first, get orgs where temp is true
        $newOrgs = $this->em->createQuery(
                        "SELECT o FROM TruckeeMatchBundle:Organization o "
                        . " WHERE o.temp = '1'")->getResult();
        $incoming = [];
        foreach ($newOrgs as $key => $org) {
            $incoming[$key]['org'] = $org;
            $name = $org->getOrgname();
            $hasDupe = $this->getOrgNames($name);
            $incoming[$key]['dupes'] = $hasDupe;
        }

        return $incoming;
    }

    public function getMatchedVolunteers($id)
    {
        $opportunity = $this->em->getRepository("TruckeeMatchBundle:Opportunity")->find($id);
        $skills = $opportunity->getSkills()->toArray();
        $organization = $opportunity->getOrganization();
        $focuses = $organization->getFocuses()->toArray();

        $criteria['skills'] = [];
        foreach ($skills as $skill) {
            $criteria['skills'][] = $skill->getId();
        }
        $criteria['focuses'] = [];
        foreach ($focuses as $focus) {
            $criteria['focuses'][] = $focus->getId();
        }
        $criteria['opportunity'] = $opportunity;
        $matched = $this->em->getRepository("TruckeeMatchBundle:Volunteer")->getMatchedVolunteers($criteria['focuses'], $criteria['skills']);

        return [
            'volunteers'    => $matched,
            'criteria'      => $criteria,
        ];
    }
}
