<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Controller\ProfileController.php

namespace Truckee\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends Controller
{

    /**
     * @Route("/")
     */
    public function showAction()
    {
        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $user = $this->getUser();

            return $this->render('FOSUserBundle/views/Profile/show.html.twig',
                    array(
                    'user' => $user,
            ));
        } else {
            return $this->redirect($this->generateUrl('home'));
        }
    }

    /**
     * @Route("/edit")
     */
    public function editAction()
    {
        $user = $this->getUser();
        $userType = $user->getUserType();
        switch ($userType) {
            case 'admin';
                return $this->adminProfileAction();
            case 'staff';
                return $this->staffProfileAction();
            case 'volunteer':
                return $this->volunteerProfileAction();
            default:
                return;
        }
    }

    private function volunteerProfileAction()
    {
        $tools = $this->container->get('truckee_match.toolbox');
        $templates = $tools->getVolunteerTemplates('profile');

        return $this->container
                ->get('pugx_multi_user.profile_manager')
                ->edit('Truckee\MatchBundle\Entity\Volunteer', $templates);
    }

    private function staffProfileAction()
    {
        $tools = $this->container->get('truckee_match.toolbox');
        $templates = $tools->getStaffTemplates('profile');

        return $this->container
                ->get('pugx_multi_user.profile_manager')
                ->edit('Truckee\MatchBundle\Entity\Staff', $templates);
    }

    private function adminProfileAction()
    {
        return $this->container
                ->get('pugx_multi_user.profile_manager')
                ->edit('Truckee\MatchBundle\Entity\Admin');
    }
}
