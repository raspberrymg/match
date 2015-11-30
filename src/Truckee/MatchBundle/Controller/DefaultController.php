<?php

namespace Truckee\MatchBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Truckee\MatchBundle\Form\MatchSearchType;

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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userOptions['focus_required'] = $this->getParameter('focus_required');
        $userOptions['skill_required'] = $this->getParameter('skill_required');

        $form = $this->createForm(new MatchSearchType($user, $userOptions));
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $tool = $this->container->get('truckee_match.toolbox');
            $data = $request->get('match_search');
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

        $tools = $this->container->get('truckee_match.toolbox');
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
     * @Template("default/volunteer.html.twig")
     */
    public function volunteerAction()
    {
        return array(
            'title' => 'Volunteering',
        );
    }

    /**
     * @Route("/non-profits", name="nonprofits")
     * @Template("default/nonProfit.html.twig")
     */
    public function nonProfitAction()
    {
        return array(
            'title' => 'Non-profits',
        );
    }

    /**
     * @Route("/about-us", name="about_us")
     * @Template("default/aboutUs.html.twig")
     */
    public function aboutUsAction()
    {
        return array(
            'title' => 'About us',
        );
    }

    /**
     * @Route("/contact-us", name="contact_us")
     * @Template("default/contactUs.html.twig")
     */
    public function contactUsAction()
    {
        return array(
            'title' => 'Contact us',
        );
    }
}
