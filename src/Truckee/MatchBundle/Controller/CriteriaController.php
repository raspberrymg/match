<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Controller\CriteriaController.php

namespace Truckee\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Truckee\MatchBundle\Form\FocusesType;
use Truckee\MatchBundle\Form\FocusType;
use Truckee\MatchBundle\Entity\Focus;
use Truckee\MatchBundle\Form\SkillsType;
use Truckee\MatchBundle\Form\SkillType;
use Truckee\MatchBundle\Entity\Skill;

/**
 * Description of CriteriaController
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class CriteriaController extends Controller
{

    /**
     * @Route("/editFocus", name="focus_edit")
     * @Template("Criteria/editFocus.html.twig")
     */
    public function editFocusAction(Request $request)
    {
        $flash   = $this->get('braincrafted_bootstrap.flash');
        $doFocus = $this->container->getParameter('focus_required');
        if (!$doFocus) {
            $flash->error('Focus criteria not enabled');
            
            return $this->redirect($this->generateUrl("admin_home"));
        }
        $em = $this->getDoctrine()->getManager();

        $focuses     = $em->getRepository("TruckeeMatchBundle:Focus")->getFocusesNoAll();
        $focusAll    = $em->getRepository("TruckeeMatchBundle:Focus")->findOneBy(['focus' => 'All']);
        $focusAllId  = $focusAll->getId();
        $formFocuses = $this->createForm(new FocusesType(),
            array('focuses' => $focuses));
        $focus       = new Focus();
        $formFocus   = $this->createForm(new FocusType, $focus);

        $tools         = $this->container->get('truckee_match.toolbox');
        $criteriaUsage = $tools->usageFocusSkill();

        if ($request->getMethod() == 'POST') {
            $formFocus->handleRequest($request);
            $formFocuses->handleRequest($request);
            foreach ($focuses as $existingFocus) {
                $em->persist($existingFocus);
            }
            //avoid null new focus
            $newFocus = $request->request->get('focus');
            if ('' <> $newFocus['focus'] && $formFocus->isValid()) {
                $em->persist($focus);
            }

            $em->flush();
            $flash->success('Focus criteria updated');

            return $this->redirect($this->generateUrl("focus_edit"));
        }

        return ['formFocuses' => $formFocuses->createView(),
            'formFocus' => $formFocus->createView(),
            'fociUsage' => $criteriaUsage['fociUsage'],
            'allId' => $focusAllId,
            'title' => 'Edit focuses',
        ];
    }

    /**
     * @Route("/editSkill", name="skill_edit")
     * @Template("Criteria/editSkill.html.twig")
     */
    public function editSkillAction(Request $request)
    {
        $flash   = $this->get('braincrafted_bootstrap.flash');
        $doSkill = $this->container->getParameter('skill_required');
        if (!$doSkill) {
            $flash->error('Skill criteria not enabled');

            return $this->redirect($this->generateUrl("admin_home"));
        }
        $em         = $this->getDoctrine()->getManager();
        $skills     = $em->getRepository("TruckeeMatchBundle:Skill")->getSkillsNoAll();
        $skillAll   = $em->getRepository("TruckeeMatchBundle:Skill")->findOneBy(['skill' => 'All']);
        $skillAllId = $skillAll->getId();
        $formSkills = $this->createForm(new SkillsType(),
            array('skills' => $skills));
        $skill      = new Skill();
        $formSkill  = $this->createForm(new SkillType, $skill);

        $tools         = $this->container->get('truckee_match.toolbox');
        $criteriaUsage = $tools->usageFocusSkill();

        if ($request->getMethod() == 'POST') {

            $formSkill->handleRequest($request);
            $formSkills->handleRequest($request);
            foreach ($skills as $existingSkill) {
                $em->persist($existingSkill);
            }
            //avoid null new skill
            $newSkill = $request->request->get('skill');
            if ('' <> $newSkill['skill'] && $formSkill->isValid()) {
                $em->persist($skill);
            }

            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Skill criteria updated');

            return $this->redirect($this->generateUrl("skill_edit"));
        }

        return [
            'formSkills' => $formSkills->createView(),
            'formSkill' => $formSkill->createView(),
            'skillsUsage' => $criteriaUsage['skillsUsage'],
            'allId' => $skillAllId,
            'title' => 'Edit skills',
        ];
    }
}
