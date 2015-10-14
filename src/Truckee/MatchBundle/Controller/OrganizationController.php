<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Controller\OrganizationController

namespace Truckee\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Truckee\MatchBundle\Form\OrganizationType;
use Truckee\MatchBundle\Form\OrganizationSelectType;

/**
 * Description of Organization
 * @Route("/org")
 * @Security("is_granted('ROLE_STAFF')")
 * @author George
 */
class OrganizationController extends Controller
{

    /**
     * @Route("/edit/{id}", name="org_edit")
     * @Template()
     */
    public function editAction(Request $request, $id = null)
    {
        $user = $this->getUser();
        $tool = $this->container->get('truckee_match.toolbox');
        $type = $tool->getUserType($user);
        $em = $this->getDoctrine()->getManager();

        $organization = ('staff' === $type) ? $user->getOrganization() :
                $em->getRepository("TruckeeMatchBundleOrganization")->find($id);
        $name = $organization->getOrgName();

        $similarNames = array();
        if ($organization->getTemp()) {
            $similarNames = $tool->getOrgNames($name);
        }
        $form = $this->createForm(new OrganizationType(), $organization);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($organization);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success($name . " updated");

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
            'errors' => $errors,
            'title' => 'Edit organization',
            'similars' => $similarNames,
        );
    }

    /**
     * @Route("/select", name="org_select")
     * @Template()
     */
    public function orgSelectAction(Request $request)
    {
        $form = $this->createForm(new OrganizationSelectType());
        if ($request->isMethod('POST')) {
            $org = $this->get('request')->request->get('org_select');
            $id = $org['organization'];
            if ('' === $id) {
                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->alert("No organization selected");
            }
            else {

                return $this->redirect($this->generateUrl('org_edit', array(
                                    'id' => $id,
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'title' => 'Select organization'
        );
    }
}
