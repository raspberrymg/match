<?php

/*
 * This file is part of the App package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\RegistrationController

namespace AppBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/register")
 */
class RegistrationController extends BaseController
{
    /**
     * @Route("/staff", name="reg_staff")
     * @return type
     */
    public function registerStaffAction(Request $request)
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register('AppBundle\Entity\Staff');
    }

    /**
     * @Route("/volunteer", name="reg_volunteer")
     * @return type
     */
    public function registerVolunteerAction()
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register('AppBundle\Entity\Volunteer');
    }

    /**
     * @Route("/admin", name="reg_admin")
     * @return type
     */
    public function registerAdminAction()
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register('AppBundle\Entity\Admin');
    }
}
