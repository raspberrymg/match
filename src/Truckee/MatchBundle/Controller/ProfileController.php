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
     * @Route("/edit")
     */
    public function editAction()
    {
        $user = $this->getUser();
        $tools = $this->container->get('truckee_match.toolbox');
        $userType = $tools->getUserType($user);
        switch ($userType) {
            case 'volunteer':
                return $this->volunteerProfileAction();

                break;

            default:
                break;
        }
    }

    private function volunteerProfileAction()
    {
        $focusRequired = $this->getParameter('focus_required');
        $skillRequired = $this->getParameter('skill_required');
        $tools = $this->container->get('truckee_match.toolbox');
        $templates = $tools->getVolunteerTemplates($focusRequired, $skillRequired, 'profile');

        return $this->container
                ->get('pugx_multi_user.profile_manager')
                ->edit('Truckee\MatchBundle\Entity\Volunteer', $templates);
    }
}
