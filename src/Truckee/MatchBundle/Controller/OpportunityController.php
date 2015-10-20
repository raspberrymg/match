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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Truckee\MatchBundle\Entity\Opportunity;
use Truckee\MatchBundle\Form\OpportunityType;

/**
 * @Route("/opp")
 * @Security("has_role('ROLE_STAFF')")
 */
class OpportunityController extends Controller
{

    /**
     * Create a new opportunity for organization
     * If user is staff, then for that org
     * if user is admin, then any org
     * 
     * @Route("/new/{id}", name="opp_new")
     * @Template("TruckeeMatchBundle:Opportunity:manage.html.twig")
     */
    public function newAction(Request $request, $id = null)
    {
        $user = $this->getUser();
        $tool = $this->container->get('truckee.toolbox');
        $type = $tool->getUserType($user);
        if (false === $this->get('security.context')->isGranted('ROLE_STAFF')) {
            throw $this->AccessDeniedException('You do not have permission to create an opportunity');
        }
        $em = $this->getDoctrine()->getManager();
        $opportunity = new Opportunity();
        if (null === $id) {
            $organization = $user->getOrganization();
        }
        else {
            $organization = $em->getRepository("TruckeeMatchBundle:Organization")->find($id);
        }
        $opportunity->setOrganization($organization);
        $form = $this->createForm(new OpportunityType(), $opportunity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $date = new \DateTime();
            if (is_null($opportunity->getExpireDate())) {
                $opportunity->setExpireDate($date->add(new \DateInterval('P1Y')));
            }
            $opportunity->setAddDate($date);
            $em->persist($opportunity);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success("Opportunity added");

            if ('staff' === $type) {
                return $this->redirect($this->generateUrl('org_edit'));
            }
            else {
                return $this->redirect($this->generateUrl('admin_home'));
            }
        }
        $errors = $form->getErrorsAsString();

        return array(
            'form' => $form->createView(),
            'organization' => $organization,
            'title' => 'New opportunity',
            'opp' => $opportunity,
            'errors' => $errors,
            'method' => 'New'
        );
    }

    /**
     * @Route("/edit/{id}", name="opp_edit")
     * @Template("TruckeeMatchBundle:Opportunity:manage.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->getUser();
        $tool = $this->container->get('truckee.toolbox');
        $type = $tool->getUserType($user);
        if ($type <> 'admin' && $type <> 'staff') {
            throw $this->createNotFoundException('You do not have permission to edit an opportunity');
        }
        $em = $this->getDoctrine()->getManager();
        $opportunity = $em->getRepository("TruckeeMatchBundle:Opportunity")->find($id);
        $organization = $opportunity->getOrganization();
        $form = $this->createForm(new OpportunityType(), $opportunity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $opportunity->setLastupdate(new \DateTime());
            $organization = $opportunity->getOrganization();
            $orgId = $organization->getId();
            $em->persist($opportunity);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success("Opportunity updated");

            if ('staff' === $type) {
                return $this->redirect($this->generateUrl('org_edit', array('id' => $orgId)));
            }
            else {
                return $this->redirect($this->generateUrl('admin_home'));
            }
        }
        $errors = $form->getErrorsAsString();

        return array(
            'form' => $form->createView(),
            'opp' => $opportunity,
            'organization' => $organization,
            'error' => $errors,
            'title' => 'Edit opportunity',
            'method' => 'Edit'
        );
    }
}
