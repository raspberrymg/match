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
 * Description of Organization.
 *
 * @Route("/org")
 * @Security("is_granted('ROLE_STAFF')")
 *
 * @author George
 */
class OrganizationController extends Controller
{
    /**
     * @Route("/edit/{id}", name="org_edit")
     * @Template("Organization/orgEdit.html.twig")
     */
    public function editAction(Request $request, $id = null)
    {
        $user = $this->getUser();
        $type = $user->getUserType();
        $em = $this->getDoctrine()->getManager();
        $tool = $this->container->get('truckee_match.toolbox');

        $organization = ('staff' === $type) ? $user->getOrganization() :
            $em->getRepository('TruckeeMatchBundle:Organization')->find($id);
        $name = $organization->getOrgName();
        $focus = $this->container->getParameter('focus_required');
        $form = $this->createForm(new OrganizationType($focus),
            $organization);

        //organization templates
        if ($organization->getTemp()) {
            $templates[] = 'Organization/inactiveOrganization.html.twig';
        } else {
            $templates[] = 'Organization/activeOrganization.html.twig';
        }
        $templates[] = 'Organization/orgForm.html.twig';
        if ('admin' === $type) {
            $similarNames = ($organization->getTemp()) ? $tool->getOrgNames($name)
                    : array();
            $templates[] = 'Organization/similarNames.html.twig';
            $templates[] = 'Organization/orgForm.html.twig';
            if ($focus) {
                $templates[] = 'default/focus.html.twig';
            }
            $templates[] = 'Organization/orgFormStaffEdit.html.twig';
        } else {
            $templates[] = 'Organization/orgForm.html.twig';
            if ($focus) {
                $templates[] = 'default/focus.html.twig';
            }
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($organization);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success($name.' updated');

            if ('staff' === $type) {
                return $this->redirect($this->generateUrl('org_edit'));
            } else {
                return $this->redirect($this->generateUrl('admin_home'));
            }
        }
        $errors = $form->getErrorsAsString();

        return array(
            'form' => $form->createView(),
            'organization' => $organization,
            'errors' => $errors,
            'templates' => $templates,
            'title' => 'Edit organization',
            'similars' => $similarNames,
        );
    }
}
