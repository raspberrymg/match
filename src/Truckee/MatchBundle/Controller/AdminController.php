<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Controller\AdminController.php


namespace Truckee\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Truckee\MatchBundle\Form\VolunteerEmailType;
use Truckee\MatchBundle\Form\VolunteerUsersType;
use Truckee\MatchBundle\Form\StaffAddType;
use Truckee\MatchBundle\Entity\Staff;

/**
 * Description of AdminController.
 *
 * @author George
 * 
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     * @Template()
     */
    public function adminHomeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $expire = $this->getParameter('truckee_match.expiring_alerts');
        $oppMail = $this->getParameter('truckee_match.opportunity_email');
        $options = [];
        $sent = [];
        $optionalTemplates = [];

        if (true === $expire) {
            $notSent = $em->getRepository('TruckeeMatchBundle:Opportunity')->expiringOppsNotSent();
            $sent = $em->getRepository('TruckeeMatchBundle:Opportunity')->expiringOppsSent();
            $optionalTemplates[] = 'Admin/expiringOpps.html.twig';
            $options['notSent'] = $notSent;
            $options['sent'] = [];
        }

        if (0 < count($sent)) {
            $options['sent'] = $sent;
            $optionalTemplates[] = 'Admin/sentOpps.html.twig';
        }

        if (true === $oppMail) {
            $newopps = $em->getRepository('TruckeeMatchBundle:Opportunity')->noEmails();
            $optionalTemplates[] = 'Admin/noEmails.html.twig';
            $options['newopps'] = $newopps;
        }
        $tools = $this->container->get('truckee_match.toolbox');

        $newOrgs = $tools->getIncomingOrgs();

        return $this->render('Admin/adminHome.html.twig',
                array(
                'optionalTemplates' => $optionalTemplates,
                'options' => $options,
                'newOrgs' => $newOrgs,
                'title' => 'Admin home',
        ));
    }

    /**
     * @Route("/expiring", name="expiring_alerts")
     * @Template()
     */
    public function expiringAlertsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $opportunities = $em->getRepository('TruckeeMatchBundle:Opportunity')->expiringOppsNotSent();
        $mailer = $this->container->get('admin.mailer');
        $expiredArray = $mailer->sendExpiringAlerts($opportunities);

        $flash = $this->get('braincrafted_bootstrap.flash');
        $flash->success("{$expiredArray['nOrgs']} organizations have been alerted "
            ."about {$expiredArray['nOpps']} opportunities in "
            ."{$expiredArray['nRecipients']} e-mails");

        return $this->redirect($this->generateUrl('home',
                    array(
                    'opportunities' => $opportunities,
        )));
    }

    /**
     * @Route("/matched/{id}", name="vol_matched")
     * @Template("Admin/showMatchedVolunteers.html.twig")
     */
    public function showMatchedVolunteersAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $opportunity = $em->getRepository('TruckeeMatchBundle:Opportunity')->find($id);
        $tool = $this->container->get('truckee_match.toolbox');
        $matched = $tool->getMatchedVolunteers($id);
        $volunteers = $matched['volunteers'];
        $criteria = $matched['criteria'];
        if (empty($volunteers)) {
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->alert('No volunteers match opportunity criteria');

            return $this->redirect($this->generateUrl('admin_home'));
        }
        $form = $this->createForm(new VolunteerEmailType($matched['idArray']),
            $volunteers);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $sendTo = $data['send'];
            $bcc = $em->getRepository('TruckeeMatchBundle:Volunteer')->findBy(['id' => $sendTo]);
            $mailer = $this->container->get('admin.mailer');
            $sent = ($sendTo) ? $mailer->sendNewOppMail($bcc, $opportunity,
                    $criteria) : 0;
            $flash = $this->get('braincrafted_bootstrap.flash');

            if (0 !== $sent) {
                $message = '';
                //send flash message re n = $sent e-mails sent
                $message .= (1 === $sent) ? ' e-mail was sent' : ' e-mails were sent';
                $flash->success("$sent $message");
            } else {
                $flash->alert('No e-mails were sent');
            }

            return $this->redirect($this->generateUrl('admin_home'));
        }

        return array(
            'form' => $form->createView(),
            'opportunity' => $opportunity,
            'volunteers' => $volunteers,
            'title' => 'Matched volunteers',
        );
    }

    /**
     * @Route("/activate/{id}", name="activate_org")
     */
    public function activateOrgAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $organization = $em->getRepository('TruckeeMatchBundle:Organization')->find($id);
        $temp = $organization->getTemp();
        if (true === $temp) {
            $tools = $this->container->get('truckee_match.toolbox');
            $tools->activateOrganization($id);
            $to = $em->getRepository('TruckeeMatchBundle:Staff')->getActivePersons($id);
            $mailer = $this->container->get('admin.mailer');
            $mailer->activateOrgMail($organization, $to);
            $orgName = $organization->getOrgName();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success("$orgName has been activated");
        }

        return $this->redirect($this->generateUrl('admin_home'));
    }

    /**
     * @Route("/orgdrop/{id}", name="org_drop")
     */
    public function dropOrgAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $organization = $em->getRepository('TruckeeMatchBundle:Organization')->find($id);
        $orgName = $organization->getOrgName();
        $em->remove($organization);
        $em->flush();
        $flash = $this->get('braincrafted_bootstrap.flash');
        $flash->success("$orgName has been dropped");

        return $this->redirect($this->generateUrl('admin_home'));
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @Template("Admin/Dashboard/dashboard.html.twig")
     */
    public function dashboardAction()
    {
        $dashboard = $this->container->get('truckee_match.dashboard');
        $expire = $this->getParameter('truckee_match.expiring_alerts');
        $oppMail = $this->getParameter('truckee_match.opportunity_email');
        $searchMail = $this->getParameter('truckee_match.search_email');
        $templates[] = 'Admin/Dashboard/websiteEmailHeader.html.twig';

        if ($expire || $oppMail || $searchMail) {
            if ($searchMail) {
                $data['oppSearchForm30Day'] = $dashboard->oppSearchForm30Day();
                $data['oppSearchFormAll'] = $dashboard->oppSearchFormAll();
                $templates[] = 'Admin/Dashboard/oppSearchFormEmail.html.twig';
            }
            if ($oppMail) {
                $data['newOppEmails30Day'] = $dashboard->newOppEmails30Day();
                $data['newOppEmails'] = $dashboard->newOppEmails();
                $templates[] = 'Admin/Dashboard/newOppEmail.html.twig';
            }
            if ($expire) {
                $data['expiringOppEmails30Day'] = $dashboard->expiringOppEmails30Day();
                $data['expiringOppEmails'] = $dashboard->expiringOppEmails();
                $templates[] = 'Admin/Dashboard/expiringOppEmail.html.twig';
            }
        }
        $data['newVols30Day'] = $dashboard->newVols30Day();
        $data['newVols'] = $dashboard->newVols();
        $data['volReceivingMailOn'] = $dashboard->volReceivingMailOn();
        $data['volReceivingMailOff'] = $dashboard->volReceivingMailOff();
        $data['volLocked'] = $dashboard->volLocked();
        $data['newOrg30Day'] = $dashboard->newOrg30Day();
        $data['newOrg'] = $dashboard->newOrg();
        $data['newOpps30Day'] = $dashboard->newOpps30Day();
        $data['newOpps'] = $dashboard->newOpps();
        $data['orgActive'] = $dashboard->orgActive();
        $data['orgInactive'] = $dashboard->orgInactive();
        $data['oppActive'] = $dashboard->oppActive();
        $data['oppInactive'] = $dashboard->oppInactive();
        $data['oppExpired'] = $dashboard->oppExpired();

        return [
            'dashboard' => $data,
            'templates' => $templates,
        ];
    }

    /**
     * @Route("/select/{class}", name="person_select")
     * @Template()
     */
    public function personSelectAction(Request $request, $class)
    {
        switch ($class) {
            case 'admin':
                $form = $this->createForm(new AdminUsersType());
                break;
            case 'volunteer':
                $form = $this->createForm(new VolunteerUsersType());
                break;
            default:
                break;
        }
        $form->handleRequest($request);
        if ($form->isValid()) {
            $formName = $form->getName();
            $selected = $this->get('request')->request->get($formName);
            $id = $selected['user'];
            if ('' === $id) {
                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->alert('No person selected');
            } else {
                return $this->redirect($this->generateUrl('account_lock', array(
                                    'id' => $id,
                                    'class' => $class,
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'title' => 'Select person',
            'class' => $class,
        );
    }

    /**
     * @Route("/lock/{class}/{id}", name="account_lock")
     */
    public function lockAction(Request $request, $id, $class)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('TruckeeMatchBundle:Person')->find($id);
        if ('staff' === $class) {
            $org = $person->getOrganization();
            $orgId = $person->getOrganization()->getId();
            $staff = $org->getStaff();
            $canLock = false;
            foreach ($staff as $user) {
                $canLock = ($id != $user->getId() && $user->isLocked()) ? true : false;
            }
            if (!$canLock) {
                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->alert('Cannot lock only unlocked staff person');

                return $this->redirect($this->generateUrl('org_edit', array('id' => $orgId)));
            }
        }
        $firstName = $person->getFirstname();
        $lastName = $person->getLastname();

        $userManager = $this->container->get('pugx_user_manager');
        $locked = $person->isLocked();
        $person->setLocked(!$locked);
        $userManager->updateUser($person, true);
        $flash = $this->get('braincrafted_bootstrap.flash');
        $flash->success("User $firstName $lastName updated");

        switch ($class) {
            case 'staff':
                return $this->redirect($this->generateUrl('org_edit', array('id' => $orgId)));
            default:
                return $this->redirect($this->generateUrl('admin_home'));
                break;
        }
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
                $flash->alert('No organization selected');
            } else {
                return $this->redirect($this->generateUrl('org_edit',
                            array(
                            'id' => $id,
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'title' => 'Select organization',
        );
    }

    /**
     * Adds staff member for existing organization
     * Note: /register/staff cannot accept existing organization.
     *
     * @Route("/addStaff/{orgId}", name="staff_add")
     */
    public function addStaffAction(Request $request, $orgId)
    {
        $staff = new Staff();
        $em = $this->getDoctrine()->getManager();
        $organization = $em->getRepository('TruckeeMatchBundle:Organization')->find($orgId);
        $form = $this->createForm(new StaffAddType(), $staff);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
            $discriminator->setClass('Truckee\MatchBundle\Entity\Staff');
            $userManager = $this->container->get('pugx_user_manager');
            $user = $userManager->createUser();
            $user->setUsername($data->getUsername());
            $firstName = $data->getFirstname();
            $lastName = $data->getLastname();
            $user->setFirstname($firstName);
            $user->setLastname($lastName);
            $user->setEmail($data->getEmail());
            $user->setPlainPassword($data->getPlainPassword());
            $user->setEnabled(true);
            $user->setOrganization($organization);
            $user->addRole('ROLE_STAFF');

            $userManager->updateUser($user, true);

            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
            $mailer = $this->container->get('admin.mailer');
            $mailer->sendConfirmationEmailMessage($user);

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success("User $firstName $lastName created");

            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('Staff/add.html.twig', array(
            'form' => $form->createView(),
            'organization' => $organization,
        ));
    }
}
