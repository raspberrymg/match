<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Controller\StaffController.php

namespace Truckee\MatchBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * StaffController
 * @Route("/staff")
 * @Security("has_role('ROLE_STAFF')")
 */
class StaffController extends Controller
{
    /**
     * @Route("home", name="staff_home")
     */
    public function staffHomeAction()
    {
        $flash = $this->get('braincrafted_bootstrap.flash');
        $user = $this->getUser();
        $userType = $user->getUserType();
        if ('admin' === $userType) {
            $flash->alert('Staff home page for staff only please');
            return $this->redirect($this->generateUrl('admin_home'));
        }
        $organization = $user->getOrganization();
        $opportunities = $user->getOrganization()->getOpportunities();
        return $this->render('Staff/staff_home.html.twig',
                array(
                'title' => 'Staff home',
                'organization' => $organization,
                'opportunities' => $opportunities
        ));
    }
}
