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
        $user = $this->getUser();
        $tool = $this->container->get('truckee_match.toolbox');
        $type = $tool->getUserType($user);
        if ('admin' === $type) {
            return $this->redirect($this->generateUrl("admin_home"));
        }
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
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
        $tokenStorage = $this->container->get('security.token_storage');
        $tool = $this->container->get('truckee_match.toolbox');

        $form = $this->createForm(new MatchSearchType($tokenStorage, $tool));
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $data = $request->get('match_search');

            $tool->setSearchRecord($data, 'opportunity');
            $opportunities = $em->getRepository("TruckeeMatchBundle:Opportunity")->doFocusSkillSearch($data);

            if ($opportunities) {
                return array(
                    'opportunities' => $opportunities,
                    'title' => 'Search results',
                );
            }
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->alert("No opportunities meet your criteria");
        }
        return $this->render("default/oppSearchForm.html.twig", array(
                    'form' => $form->createView(),
                    'title' => 'Search for opportunities',
        ));
    }
}
