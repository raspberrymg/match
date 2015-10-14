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
use Truckee\MatchBundle\Form\TemplateType;
use Truckee\MatchBundle\Form\AdminUsersType;
use Truckee\MatchBundle\Form\VolunteerUsersType;

/**
 * Description of AdminController
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
//        $sent = $em->getRepository('TruckeeMatchBundle:Opportunity')->expiringOppsSent();
//        $notSent = $em->getRepository('TruckeeMatchBundle:Opportunity')->expiringOppsNotSent();
//        $newopps = $em->getRepository("TruckeeMatchBundleOpportunity")->noEmails();
//        $newOrgs = $tools->getIncomingOrgs();
//        $tools = $this->container->get('truckee_match.toolbox');
        $expire = $this->getParameter('truckee_match.expiring_alerts');
        return $this->render("Admin/adminHome.html.twig", array(
            'expire' =>$expire
//                    'sent' => $sent,
//                    'notSent' => $notSent,
//                    'newopps' => $newopps,
//                    'newOrgs' => $newOrgs,
//                    'title' => 'Admin home',
        ));
    }

//    /**
//     * @Route("/dashboard", name="dashboard")
//     * @Template()
//     */
//    public function dashboardAction()
//    {
//        $dashboard = $this->container->get('truckee_match.dashboard');
//        $data['oppSearchForm30Day'] = $dashboard->oppSearchForm30Day();
//        $data['oppSearchFormAll'] = $dashboard->oppSearchFormAll();
//        $data['newOppEmails30Day'] = $dashboard->newOppEmails30Day();
//        $data['newOppEmails'] = $dashboard->newOppEmails();
//        $data['expiringOppEmails30Day'] = $dashboard->expiringOppEmails30Day();
//        $data['expiringOppEmails'] = $dashboard->expiringOppEmails();
//        $data['newVols30Day'] = $dashboard->newVols30Day();
//        $data['newVols'] = $dashboard->newVols();
//        $data['volReceivingMailOn'] = $dashboard->volReceivingMailOn();
//        $data['volReceivingMailOff'] = $dashboard->volReceivingMailOff();
//        $data['volLocked'] = $dashboard->volLocked();
//        $data['newOrg30Day'] = $dashboard->newOrg30Day();
//        $data['newOrg'] = $dashboard->newOrg();
//        $data['newOpps30Day'] = $dashboard->newOpps30Day();
//        $data['newOpps'] = $dashboard->newOpps();
//        $data['orgActive'] = $dashboard->orgActive();
//        $data['orgInactive'] = $dashboard->orgInactive();
//        $data['oppActive'] = $dashboard->oppActive();
//        $data['oppInactive'] = $dashboard->oppInactive();
//        $data['oppExpired'] = $dashboard->oppExpired();
//
//        return [
//            'oppSearchForm30Day' => $data['oppSearchForm30Day'],
//            'oppSearchFormAll' => $data['oppSearchFormAll'],
//            'newOppEmails30Day' => $data['newOppEmails30Day'],
//            'newOppEmails' => $data['newOppEmails'],
//            'expiringOppEmails30Day' => $data['expiringOppEmails30Day'],
//            'expiringOppEmails' => $data['expiringOppEmails'],
//            'newVols30Day' => $data['newVols30Day'],
//            'newVols' => $data['newVols'],
//            'volReceivingMailOn' => $data['volReceivingMailOn'],
//            'volReceivingMailOff' => $data['volReceivingMailOff'],
//            'volLocked' => $data['volLocked'],
//            'newOrg30Day' => $data['newOrg30Day'],
//            'newOrg' => $data['newOrg'],
//            'newOpps30Day' => $data['newOpps30Day'],
//            'newOpps' => $data['newOpps'],
//            'orgActive' => $data['orgActive'],
//            'orgInactive' => $data['orgInactive'],
//            'oppActive' => $data['oppActive'],
//            'oppInactive' => $data['oppInactive'],
//            'oppExpired' => $data['oppExpired'],
//        ];
//    }
//
//    /**
//     * @Route("/activate/{id}", name="activate_org")
//     */
//    public function activateOrgAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $organization = $em->getRepository("TruckeeMatchBundleOrganization")->find($id);
//        $temp = $organization->getTemp();
//        if (true === $temp) {
//            $organization->setTemp(false);
//            $organization->setActive(true);
//            $organization->setAddDate(new \DateTime());
//            $orgName = $organization->getOrgName();
//            $em->persist($organization);
//            $em->flush();
//            $to = $em->getRepository("TruckeeMatchBundleStaff")->getActivePersons($id);
//            $mailer = $this->container->get('admin.mailer');
//            $mailer->activateOrgMail($organization, $to);
//            $flash = $this->get('braincrafted_bootstrap.flash');
//            $flash->success("$orgName has been activated");
//        }
//
//        return $this->redirect($this->generateUrl('admin_home'));
//    }
//
//    /**
//     * @Route("/orgdrop/{id}", name="org_drop")
//     */
//    public function dropOrgAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $organization = $em->getRepository("TruckeeMatchBundleOrganization")->find($id);
//        $orgName = $organization->getOrgName();
//        $em->remove($organization);
//        $em->flush();
//        $flash = $this->get('braincrafted_bootstrap.flash');
//        $flash->success("$orgName has been dropped");
//
//        return $this->redirect($this->generateUrl("admin_home"));
//    }
//
//    /**
//     * @Route("/expiring", name="expiring_alerts")
//     * @Template()
//     */
//    public function expiringAlertsAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//        $opportunities = $em->getRepository('TruckeeMatchBundle:Opportunity')->expiringOppsNotSent();
//
//        $nOpps = count($opportunities);
//        $expiring = [];
//        $nOrgs = 0;
//        $org = '';
//        foreach ($opportunities as $opp) {
//            if ($org <> $opp->getOrganization()) {
//                $org = $opp->getOrganization();
//                $nOrgs++;
//            }
//            foreach ($org->getStaff() as $user) {
//                $id = $user->getId();
//                if (!array_key_exists($id, $expiring)) {
//                    $expiring[$id]['user'] = $user;
//                    $expiring[$id]['orgName'] = $org->getOrgName();
//                    $expiring[$id]['oppData'] = [];
//                }
//                $expiring[$id]['oppData'][] = [
//                    'oppId' => $opp->getId(),
//                    'orgId' => $org->getId(),
//                    'oppName' => $opp->getOppName(),
//                    'expireDate' => $opp->getExpireDate(),
//                ];
//            }
//        }
//        $mailer = $this->container->get('admin.mailer');
//        $recipientCount = $mailer->sendExpiringAlerts($expiring);
//
//        //populate admin_outbox
//        $recipientArray = [];
//        foreach ($expiring as $key => $value) {
//            $recipientId = $key;
//            foreach ($value['oppData'] as $opp) {
//                $recipientArray['function'] = 'expiringAlertsAction';
//                $recipientArray['messageType'] = 'to';
//                $recipientArray['oppId'] = $opp['oppId'];
//                $recipientArray['orgId'] = $opp['orgId'];
//                $recipientArray['id'] = $recipientId;
//                $recipientArray['userType'] = 'staff';
//            }
//            $mailer->populateAdminOutbox($recipientArray);
//        }
//        $flash = $this->get('braincrafted_bootstrap.flash');
//        $flash->success("$nOrgs organizations have been alerted about $nOpps opportunities in $recipientCount e-mails");
//
//        return $this->redirect($this->generateUrl('home', array(
//                            'opportunities' => $opportunities,
//        )));
//    }
//
//    /**
//     * @Route("/matched/{id}", name="vol_matched")
//     * @Template()
//     */
//    public function showMatchedVolunteersAction(Request $request, $id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $opportunity = $em->getRepository("TruckeeMatchBundleOpportunity")->find($id);
//        $skills = $opportunity->getSkills()->toArray();
//        $organization = $opportunity->getOrganization();
//        $focuses = $organization->getFocuses()->toArray();
//
//        $criteria['skills'] = [];
//        foreach ($skills as $skill) {
//            $criteria['skills'][] = $skill->getId();
//        }
//        $criteria['focuses'] = [];
//        foreach ($focuses as $focus) {
//            $criteria['focuses'][] = $focus->getId();
//        }
//        $criteria['opportunity'] = $opportunity;
//
//        $matched = $em->getRepository("TruckeeMatchBundleVolunteer")->getVolunteerVolunteers($criteria['focuses'], $criteria['skills']);
//        if (empty($matched)) {
//            $flash = $this->get('braincrafted_bootstrap.flash');
//            $flash->alert("No volunteers match opportunity criteria");
//
//            return $this->redirect($this->generateUrl("admin_home"));
//        }
//        foreach ($matched as $match) {
//            $idArray[$match['id']] = $match['id'];
//        }
//        $volunteers = $em->getRepository("TruckeeMatchBundleVolunteer")->findById($idArray);
//        $form = $this->createForm(new VolunteerEmailType($idArray), $volunteers);
//        $form->handleRequest($request);
//        if ($form->isValid()) {
//
//            $data = $form->getData();
//            $send = $data['send'];
//            $bcc = $em->getRepository("TruckeeMatchBundleVolunteer")->findBy(['id' => $send]);
//            $mailer = $this->container->get('admin.mailer');
//            $sent = ($send) ? $mailer->sendNewOppMail($bcc, $opportunity) : 0;
//            $flash = $this->get('braincrafted_bootstrap.flash');
//
//            if (0 !== $sent) {
//                //record search criteria
//                $tool = $this->container->get('truckee_match.toolbox');
//                $tool->setSearchRecord($criteria, 'volunteer');
//                $oppId = $opportunity->getId();
//                $orgId = $organization->getId();
//                $recipientArray = [];
//                foreach ($bcc as $recipient) {
//                    $recipientArray['function'] = 'showMatchedVolunteersAction';
//                    $recipientArray['messageType'] = 'bcc';
//                    $recipientArray['oppId'] = $oppId;
//                    $recipientArray['orgId'] = $orgId;
//                    $recipientArray['id'] = $recipient->getId();
//                    $recipientArray['userType'] = 'volunteer';
//                }
//                //save entry to admin outbox
////                $recipients = [
////                    'recipients' => $bcc,
////                    'userType' => 'volunteer',
////                    'messageType' => 'bcc',
////                    'function' => 'showMatchedVolunteersAction',
////                    'oppId' => $opportunity->getId(),
////                    'orgId' => $organization->getId(),
////                ];
//                $mailer->populateAdminOutbox($recipientArray);
//
//                $message = '';
//                //send flash message re n = $sent e-mails sent
//                $message .= (1 === $sent) ? ' e-mail was sent' : ' e-mails were sent';
//                $flash->success("$sent $message");
//            }
//            else {
//                $flash->alert("No e-mails were sent");
//            }
//
//            return $this->redirect($this->generateUrl("admin_home"));
//        }
//        $errors = $form->getErrorsAsString();
//
//        return array(
//            'form' => $form->createView(),
//            'errors' => $errors,
//            'opportunity' => $opportunity,
//            'volunteers' => $volunteers,
//            'title' => 'Matched volunteers',
//        );
//    }
//
//    /**
//     *
//     * @Route("/templateEdit/{id}", name="template_edit")
//     * @Template("TruckeeMatchBundleTemplate:edit.html.twig")
//     */
//    public function templateEditAction(Request $request, $id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $template = $em->getRepository("TruckeeMatchBundleTemplate")->find($id);
//        $form = $this->createForm(new TemplateType(), $template);
//        $form->handleRequest($request);
//        if ($form->isValid()) {
//            $template->setLastModified(new \DateTime());
//            $name = $template->getName();
//            $em->persist($template);
//            $em->flush();
//            // remove previously cached version
//            $fileCache = $this->container->get('twig')->getCacheFilename($name);
//            if (is_file($fileCache)) {
//                @unlink($fileCache);
//            }
//            $flash = $this->get('braincrafted_bootstrap.flash');
//            $flash->success("Template $name updated");
//
//            return $this->redirect($this->generateUrl("admin_home"));
//        }
//
//        return array(
//            'form' => $form->createView(),
//            'title' => 'Template edit',
//        );
//    }
//
//    /**
//     * @Route("/select/{class}", name="person_select")
//     * @Template()
//     */
//    public function personSelectAction(Request $request, $class)
//    {
//        switch ($class) {
//            case 'admin':
//                $form = $this->createForm(new AdminUsersType());
//                break;
//            case 'volunteer':
//                $form = $this->createForm(new VolunteerUsersType());
//                break;
//            default:
//                break;
//        }
//        $form->handleRequest($request);
//        if ($form->isValid()) {
//            $formName = $form->getName();
//            $selected = $this->get('request')->request->get($formName);
//            $id = $selected['user'];
//            if ('' === $id) {
//                $flash = $this->get('braincrafted_bootstrap.flash');
//                $flash->alert("No person selected");
//            }
//            else {
//                return $this->redirect($this->generateUrl('account_lock', array(
//                                    'id' => $id,
//                                    'class' => $class,
//                )));
//            }
//        }
//        return array(
//            'form' => $form->createView(),
//            'title' => 'Select person',
//            'class' => $class,
//        );
//    }
//
//    /**
//     * @Route("/lock/{class}/{id}", name="account_lock")
//     */
//    public function lockAction(Request $request, $id, $class)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $person = $em->getRepository("TruckeeMatchBundlePerson")->find($id);
//        if ('staff' === $class) {
//            $org = $person->getOrganization();
//            $orgId = $person->getOrganization()->getId();
//            $staff = $org->getStaff();
//            $canLock = false;
//            foreach ($staff as $user) {
//                $canLock = ($id <> $user->getId() && $user->isLocked()) ? true : false;
//            }
//            if (!$canLock) {
//                $flash = $this->get('braincrafted_bootstrap.flash');
//                $flash->alert("Cannot lock only unlocked staff person");
//                return $this->redirect($this->generateUrl('org_edit', array('id' => $orgId)));
//            }
//        }
//        $firstName = $person->getFirstname();
//        $lastName = $person->getLastname();
//
//        $userManager = $this->container->get('pugx_user_manager');
//        $locked = $person->isLocked();
//        $person->setLocked(!$locked);
//        $userManager->updateUser($person, true);
//        $flash = $this->get('braincrafted_bootstrap.flash');
//        $flash->success("User $firstName $lastName updated");
//
//        switch ($class) {
//            case 'staff':
//                return $this->redirect($this->generateUrl('org_edit', array('id' => $orgId)));
//            default:
//                return $this->redirect($this->generateUrl('admin_home'));
//                break;
//        }
//    }
}
