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
use Truckee\MatchBundle\Entity\AdminOutbox;

/**
 * a set of tools requiring entity manager.
 */
class Toolbox
{
    private $em;
    private $options;
    private $focusRequired;
    private $skillRequired;

    public function __construct(EntityManager $em, $userOptions)
    {
        $this->em = $em;
        $this->options = $userOptions;
        $this->focusRequired = $userOptions['focus_required'];
        $this->skillRequired = $userOptions['skill_required'];
    }

    public function getTypeFromId($id)
    {
        $user = $this->em->getRepository('TruckeeMatchBundle:Person')->find($id);

        return $user->getUserType();
    }

    public function getOptions()
    {
        return $this->options;
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
        $focusMatched = $skillMatched = array();
        $opportunity = $this->em->getRepository('TruckeeMatchBundle:Opportunity')->find($id);
        $criteria['opportunity'] = $opportunity;
        if (true === $this->focusRequired) {
            $organization = $opportunity->getOrganization();
            $focuses = $organization->getFocuses()->toArray();
            $criteria['focuses'] = [];
            foreach ($focuses as $focus) {
                $criteria['focuses'][] = $focus->getId();
            }
            $focusMatched = $this->em->getRepository('TruckeeMatchBundle:Volunteer')->getVolunteersByFocus($criteria['focuses']);
        }
        if (true === $this->skillRequired) {
            $skills = $opportunity->getSkills()->toArray();
            $criteria['skills'] = [];
            foreach ($skills as $skill) {
                $criteria['skills'][] = $skill->getId();
            }
            $skillMatched = $this->em->getRepository('TruckeeMatchBundle:Volunteer')->getVolunteersBySkill($criteria['skills']);
            foreach ($skills as $skill) {
                $criteria['skills'][] = $skill->getId();
            }
        }

        $matchedArray = array_unique(array_merge($focusMatched, $skillMatched),
            SORT_REGULAR);
        foreach ($matchedArray as $volunteer) {
            $idArray[$volunteer['id']] = $volunteer['id'];
        }

        if (array() === $matchedArray) {
            $matched = $this->em->getRepository('TruckeeMatchBundle:Volunteer')->findBy(array(
                'receiveEmail' => true, 'enabled' => true, ));
            foreach ($matched as $volunteer) {
                $id = $volunteer->getId();
                $idArray[$id] = $id;
            }
        } else {
            $matched = $this->em->getRepository('TruckeeMatchBundle:Volunteer')->findById($idArray);
        }

        return array(
            'volunteers' => $matched,
            'criteria' => $criteria,
            'idArray' => $idArray,
        );
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

    public function getStaffTemplates($method)
    {
        $em = $this->em;
        $submit = true;
        $templates = $this->templatesPerson($method, 'staff');
        if ($this->focusRequired && 'register' === $method) {
            $templates[] = 'Organization/orgFocus.html.twig';
            $nFocuses = $em->getRepository('TruckeeMatchBundle:Focus')->countFocuses();
            $submit = (1 < $nFocuses) ? $submit : false;
        }
        if (true === $submit) {
            $templates[] = 'default/save.html.twig';
        }

        return $templates;
    }

    public function getVolunteerTemplates($method)
    {
        $em = $this->em;
        $submit = true;
        $templates = array_merge($this->templatesPerson($method, 'volunteer'),
            $this->templatesFocusSkill());
        if ($this->focusRequired) {
            $nFocuses = $em->getRepository('TruckeeMatchBundle:Focus')->countFocuses();
            $submit = (1 < $nFocuses) ? $submit : false;
        }
        if ($this->skillRequired) {
            $nSkills = $em->getRepository('TruckeeMatchBundle:Skill')->countSkills();
            $submit = (1 < $nSkills) ? $submit : false;
        }
        if (true === $submit) {
            $templates[] = 'default/save.html.twig';
        }

        return $templates;
    }

    public function templatesFocusSkill()
    {
        $templates = array();
        if ($this->focusRequired) {
            $templates[] = 'default/focus.html.twig';
        }
        if ($this->skillRequired) {
            $templates[] = 'default/skill.html.twig';
        }

        return $templates;
    }

    private function templatesPerson($method, $userType)
    {
        $templates[] = 'Person/person_manage.html.twig';
        switch ($method) {
            case 'register':
                $templates[] = 'Person/registerPassword.html.twig';
                if ('staff' === $userType) {
                    $templates[] = 'Organization/registerOrgForm.html.twig';
                }
                break;
            case 'profile':
                $templates[] = 'Person/currentPassword.html.twig';
                if ('volunteer' === $userType) {
                    $templates[] = 'Volunteer/receiveEmail.html.twig';
                }
            default:
                break;
        }

        return $templates;
    }

    public function getAdminRecipients()
    {
        $em = $this->em;
        $admins = $em->getRepository('TruckeeMatchBundle:Admin')->findBy(['locked' => false]);
        $adminEmail = [];
        foreach ($admins as $admin) {
            $email = $admin->getEmail();
            $adminEmail[] = $email;
        }

        return $adminEmail;
    }

    /**
     * populate AdminOutbox.
     */
    public function populateAdminOutbox($recipientArray)
    {
        if (array_key_exists('function', $recipientArray)) {
            $outbox = new AdminOutbox();
            $outbox->setDate(new \DateTime());
            $outbox->setFunction($recipientArray['function']);
            $outbox->setMessageType($recipientArray['messageType']);
            $outbox->setOppId($recipientArray['oppId']);
            $outbox->setOrgId($recipientArray['orgId']);
            $outbox->setRecipientId($recipientArray['id']);
            $outbox->setUserType($recipientArray['userType']);
            $this->em->persist($outbox);
        } else {
            foreach ($recipientArray as $recipient) {
                $outbox = new AdminOutbox();
                $outbox->setDate(new \DateTime());
                $outbox->setFunction($recipient['function']);
                $outbox->setMessageType($recipient['messageType']);
                $outbox->setOppId($recipient['oppId']);
                $outbox->setOrgId($recipient['orgId']);
                $outbox->setRecipientId($recipient['id']);
                $outbox->setUserType($recipient['userType']);
                $this->em->persist($outbox);
            }
        }
        $this->em->flush();
    }
}
