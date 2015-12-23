<?php
/*
 * This file is part of the Truckee\Volunteer package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Controller\OpportunityController

namespace Truckee\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Truckee\MatchBundle\Entity\Opportunity;
use Truckee\MatchBundle\Form\OpportunityType;
use Truckee\MatchBundle\Form\OpportunitySelectType;

/**
 * @Route("/opp")
 * @Security("has_role('ROLE_STAFF')")
 */
class OpportunityController extends Controller
{

    /**
     * Create a new opportunity for organization
     * If user is staff, then for that org
     * if user is admin, then any org.
     *
     * @Route("/new/{id}", name="opp_new")
     * @Template("Opportunity/oppManage.html.twig")
     */
    public function newAction(Request $request, $id = null)
    {
        $user = $this->getUser();
        $type = $user->getUserType();
        $em = $this->getDoctrine()->getManager();
        $skills = $this->container->getParameter('skill_required');

        $opportunity = new Opportunity();
        if (null === $id) {
            $organization = $user->getOrganization();
        } else {
            $organization = $em->getRepository('TruckeeMatchBundle:Organization')->find($id);
        }

        $templates[] = 'Opportunity/newOpportunity.html.twig';
        $templates[] = 'Opportunity/opportunityData.html.twig';
        $skillTemplates = $this->skillTemplates($skills);
        foreach ($skillTemplates as $template) {
            $templates[] = $template;
        }

        $opportunity->setOrganization($organization);
        $form   = $this->createForm(new OpportunityType($skills), $opportunity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $date = new \DateTime();
            if (is_null($opportunity->getExpireDate())) {
                $opportunity->setExpireDate($date->add(new \DateInterval('P1Y')));
            }
            $opportunity->setAddDate($date);
            $opportunity->setLastupdate($date);
            $em->persist($opportunity);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Opportunity added');

            if ('staff' === $type) {
                return $this->redirect($this->generateUrl('org_edit'));
            } else {
                return $this->redirect($this->generateUrl('admin_home'));
            }
        }

        return array(
            'form' => $form->createView(),
            'organization' => $organization,
            'title' => 'New opportunity',
            'opp' => $opportunity,
            'method' => 'New',
            'templates' => $templates,
        );
    }

    /**
     * @Route("/edit/{id}", name="opp_edit")
     * @Template("Opportunity/oppManage.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->getUser();
        $type = $user->getUserType();
        $em           = $this->getDoctrine()->getManager();
        $opportunity  = $em->getRepository('TruckeeMatchBundle:Opportunity')->find($id);
        $organization = $opportunity->getOrganization();
        $skills       = $this->container->getParameter('skill_required');
        
        $templates[] = 'Opportunity/existingOpportunity.html.twig';
        $templates[] = 'Opportunity/opportunityData.html.twig';
        $skillTemplates = $this->skillTemplates($skills);
        foreach ($skillTemplates as $template) {
            $templates[] = $template;
        }

        $form = $this->createForm(new OpportunityType($skills),
            $opportunity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $opportunity->setLastupdate(new \DateTime());
            $organization = $opportunity->getOrganization();
            $orgId        = $organization->getId();
            $em->persist($opportunity);
            $em->flush();
            $flash        = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Opportunity updated');

            if ('staff' === $type) {
                return $this->redirect($this->generateUrl('staff_home'));
            } else {
                return $this->redirect($this->generateUrl('admin_home'));
            }
        }

        return array(
            'form' => $form->createView(),
            'opp' => $opportunity,
            'organization' => $organization,
            'title' => 'Edit opportunity',
            'method' => 'Edit',
            'templates' => $templates,
        );
    }

    /**
     * @Route("/select/{id}", name="opp_select")
     */
    public function oppSelectAction($id)
    {
        $em       = $this->getDoctrine()->getManager();
        $form     = $this->createForm(new OpportunitySelectType($id));
        $content  = $this->renderView("Opportunity/oppSelect.html.twig",
            array(
            'form' => $form->createView(),
        ));
        $response = new Response($content);

        return $response;
    }

    private function skillTemplates($skills)
    {
        $save = true;
        $em = $this->getDoctrine()->getManager();
        if (TRUE === $skills) {
            $templates[] = 'default/skill.html.twig';
            $nSkills = $em->getRepository('TruckeeMatchBundle:Skill')->countSkills();
            if ($nSkills <= 1) {
                $save = false;
            }
        }
        if (TRUE === $save) {
            $templates[] = 'default/save.html.twig';
        }

        return $templates;
    }
}
