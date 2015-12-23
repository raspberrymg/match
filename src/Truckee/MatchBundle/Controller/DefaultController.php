<?php

namespace Truckee\MatchBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Truckee\MatchBundle\Form\MatchSearchType;
use Truckee\MatchBundle\Form\OpportunityEmailType;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('admin_home'));
        }
        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_STAFF')) {
            return $this->redirect($this->generateUrl('staff_home'));
        }
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig',
                array(
                'title' => '',
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/search", name="opp_search")
     * @Template("default/oppSearch.html.twig")
     */
    public function oppSearchAction(Request $request)
    {
        $user                          = $this->get('security.token_storage')->getToken()->getUser();
        $userOptions['focus_required'] = $this->getParameter('focus_required');
        $userOptions['skill_required'] = $this->getParameter('skill_required');

        $form = $this->createForm(new MatchSearchType($user, $userOptions));
        if ($request->getMethod() == 'POST') {
            $em            = $this->getDoctrine()->getManager();
            $tool          = $this->container->get('truckee_match.toolbox');
            $data          = $request->get('match_search');
            $tool->setSearchRecord($data, 'opportunity');
            $opportunities = $em->getRepository('TruckeeMatchBundle:Opportunity')->doFocusSkillSearch($data);

            if ($opportunities) {
                return array(
                    'opportunities' => $opportunities,
                    'title' => 'Search results',
                );
            }
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->alert('No opportunities meet your criteria');
        }

        $tools     = $this->container->get('truckee_match.toolbox');
        $templates = $tools->templatesFocusSkill();

        return $this->render('default/oppSearchForm.html.twig',
                array(
                'form' => $form->createView(),
                'templates' => $templates,
                'title' => 'Search for opportunities',
        ));
    }

    /**
     * @Route("/volunteer", name="volunteer")
     * @Template("MainMenu/volunteer.html.twig")
     */
    public function volunteerAction()
    {
        return array(
            'title' => 'Volunteering',
        );
    }

    /**
     * @Route("/non-profits", name="nonprofits")
     * @Template("MainMenu/nonProfit.html.twig")
     */
    public function nonProfitAction()
    {
        return array(
            'title' => 'Non-profits',
        );
    }

    /**
     * @Route("/about-us", name="about_us")
     * @Template("MainMenu/aboutUs.html.twig")
     */
    public function aboutUsAction()
    {
        return array(
            'title' => 'About us',
        );
    }

    /**
     * @Route("/contact-us", name="contact_us")
     * @Template("MainMenu/contactUs.html.twig")
     */
    public function contactUsAction()
    {
        return array(
            'title' => 'Contact us',
        );
    }

    /**
     * @Route("/oppForm/{id}", name="opp_form")
     * @Template("default/oppEmail.html.twig")
     */
    public function oppFormAction(Request $request, $id)
    {
        $user    = $this->getUser();
        $email   = ($user) ? $user->getEmail() : null;
        $em      = $this->getDoctrine()->getManager();
        $opp     = $em->getRepository("TruckeeMatchBundle:Opportunity")->find($id);
        $oppName = $opp->getOppName();
        $org     = $opp->getOrganization();
        $orgName = $org->getOrgName();
        $form    = $this->createForm(new OpportunityEmailType($oppName,
            $orgName, $email, $id));
        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $orgId   = $org->getId();
                $to      = $em->getRepository("TruckeeMatchBundle:Staff")->getActivePersons($orgId);
                $from    = $form['from']->getData();
                $subject = $form['subject']->getData();
                $content = $form['message']->getData();

                $mailer = $this->container->get('admin.mailer');
                $sent   = $mailer->sendOppInterestEmail($to, $from, $subject,
                    $content);
                //save entry to admin outbox
                if (0 !== $sent) {
                    $recipientArray = [];
                    $oppId          = $opp->getId();
                    foreach ($to as $recipient) {
                        $recipientArray['function']    = 'oppFormAction';
                        $recipientArray['messageType'] = 'bcc';
                        $recipientArray['oppId']       = $oppId;
                        $recipientArray['orgId']       = $orgId;
                        $recipientArray['id']          = $recipient->getId();
                        $recipientArray['userType']    = 'volunteer';
                    }
                    $tool = $this->container->get('truckee_match.toolbox');
                    $tool->populateAdminOutbox($recipientArray);
                }
                $response = new Response("Email sent: ".count($recipient));

                return $response;
            }
        }

        return [
            'form' => $form->createView(),
            'id' => $id,
        ];
    }

    /**
     * @Template("default/navigation.html.twig")
     */
    public function navigationAction()
    {
        $user = $this->getUser();
        $menuTemplates[] = 'default/defaultMenu.html.twig';
        $type = NULL;
        if (null !== $user) {
            $menuTemplates[] = 'default/authorizedMenu.html.twig';
            $type = $user->getUserType();
        }
        
        return array(
            'menuTemplates' => $menuTemplates,
            'type' => $type,
            );
    }

    /**
     * @Route("/nameCheck/{name}")
     * @Template("default/nameCheck.html.twig")
     */
    public function nameCheckAction($name)
    {
        $tool = $this->container->get('truckee_match.toolbox');
        $orgs = $tool->getOrgNames($name);
        //avoid name dropdown if no matches
        if (0 === count($orgs)) {
            $response = new JsonResponse();
            $response->setData(0);
            return $response;
        }
        $mail_sender = $this->container->getParameter('admin_email');

        return array(
            'mail_sender' => $mail_sender,
            'original' => $name,
            'orgs' => $orgs,
        );
    }
}
