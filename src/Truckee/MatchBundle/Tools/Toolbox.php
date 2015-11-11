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
 * a set of tools requiring entity manager.
 */
class Toolbox
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getTypeFromId($id)
    {
        $user = $this->em->getRepository('TruckeeMatchBundle:Person')->find($id);
        return $user->getUserType();
    }

    public function setSearchRecord($data, $searched)
    {
        $search = new Search();
        $search->setDate(new \DateTime());
        $search->setType($searched);
        $em = $this->em;

        if ('opportunity' === $searched && !array_key_exists('focuses', $data) && !array_key_exists('skills',
                $data)) {
            $focus = $em->getRepository('TruckeeMatchBundle:Focus')->findOneBy(['focus' => 'All']);
            $search->setFocus($focus);
            $skill = $em->getRepository('TruckeeMatchBundle:Skill')->findOneBy(['skill' => 'All']);
            $search->setSkill($skill);
            $search->setType($searched);
            $em->persist($search);
        } else {
            if (array_key_exists('organization', $data) && '' != $data['organization']['organization']) {
                $orgId = $data['organization']['organization'];
                $organization = $em->getRepository('TruckeeMatchBundle:Organization')->find($orgId);
                $search->setOrganization($organization);
            }
            if (array_key_exists('opportunity', $data)) {
                $search->setOpportunity($data['opportunity']);
            }

            if (array_key_exists('focuses', $data)) {
                foreach ($data['focuses'] as $focusId) {
                    $searchClone = clone $search;
                    $focus = $em->getRepository('TruckeeMatchBundle:Focus')->find($focusId);
                    $searchClone->setFocus($focus);
                    $em->persist($searchClone);
                }
            }
            if (array_key_exists('skills', $data)) {
                foreach ($data['skills'] as $skillId) {
                    $skill = $em->getRepository('TruckeeMatchBundle:Skill')->find($skillId);
                    $searchClone = clone $search;
                    $searchClone->setSkill($skill);
                    $searchClone->setType($searched);
                    $em->persist($searchClone);
                }
            }
        }

        $em->flush();
    }

    public function usageFocusSkill()
    {
        $fociUsage['opportunity'] = $skillsUsage['opportunity'] = array();
        $fociUsage['volunteer'] = $skillsUsage['volunteer'] = array();

        $focuses = $this->em->getRepository('TruckeeMatchBundle:Focus')->findAll();
        foreach ($focuses as $focus) {
            $id = $focus->getId();
            $searchOpps = $this->em->getRepository('TruckeeMatchBundle:Search')->findBy(['focus' => $focus,
                'type' => 'opportunity', ]);
            $fociUsage['opportunity'][$id] = count($searchOpps);
            $searchVols = $this->em->getRepository('TruckeeMatchBundle:Search')->findBy(['focus' => $focus,
                'type' => 'volunteer', ]);
            $fociUsage['volunteer'][$id] = count($searchVols);
        }

        $skills = $this->em->getRepository('TruckeeMatchBundle:Skill')->findAll();
        foreach ($skills as $skill) {
            $id = $skill->getId();
            $searchOpps = $this->em->getRepository('TruckeeMatchBundle:Search')->findBy(['skill' => $skill,
                'type' => 'opportunity', ]);
            $skillsUsage['opportunity'][$id] = count($searchOpps);
            $searchVols = $this->em->getRepository('TruckeeMatchBundle:Search')->findBy(['skill' => $skill,
                'type' => 'volunteer', ]);
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
                'SELECT o FROM TruckeeMatchBundle:Organization o '
                ." WHERE o.temp = '1'")->getResult();
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
        $opportunity = $this->em->getRepository('TruckeeMatchBundle:Opportunity')->find($id);
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
        $matched = $this->em->getRepository('TruckeeMatchBundle:Volunteer')->getMatchedVolunteers($criteria['focuses'],
            $criteria['skills']);

        return [
            'volunteers' => $matched,
            'criteria' => $criteria,
        ];
    }

    public function activateOrganization($id)
    {
        $em = $this->em;
        $organization = $em->getRepository('TruckeeMatchBundle:Organization')->find($id);
        $organization->setTemp(false);
        $organization->setActive(true);
        $organization->setAddDate(new \DateTime());
        $orgName = $organization->getOrgName();
        $em->persist($organization);

        return $em->flush();
    }

    public function getVolunteerTemplates($focusRequired, $skillRequired,
                                          $method)
    {
        $em = $this->em;
        $nFocuses = $em->getRepository('TruckeeMatchBundle:Focus')->countFocuses();
        $nSkills = $em->getRepository('TruckeeMatchBundle:Skill')->countSkills();
        switch ($method) {
            case 'register':
                $templates[] = 'Person/person_manage.html.twig';
                $templates[] = 'Person/registerPassword.html.twig';
                break;
            case 'profile':
                $templates[] = 'Person/person_manage.html.twig';
                $templates[] = 'Person/currentPassword.html.twig';
            default:
                break;
        }
        $submit = true;
        if ($focusRequired) {
            $templates[] = 'default/focus.html.twig';
            $submit = (1 < $nFocuses) ? $submit : false;
        }
        if ($skillRequired) {
            $templates[] = 'default/skill.html.twig';
            $submit = (1 < $nSkills) ? $submit : false;
        }
        if (true === $submit) {
            $templates[] = 'default/save.html.twig';
        }

        return $templates;
    }
}
