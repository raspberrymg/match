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
        $type = $user->getUserType();
        $tool = $this->container->get('truckee_match.toolbox');
        $wtf = $tool->getTypeFromId(2);
        dump($wtf);
        if ('admin' === $type) {
            return $this->redirect($this->generateUrl('admin_home'));
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

//            $search['organization'] = $record['organization'] = $data['organization'];
//            if (!array_key_exists('focuses', $data) && !array_key_exists('skills', $data)) {
//                $record['focuses'] = $em->getRepository("TruckeeMatchBundle:Focus")->findBy(['focus' => 'All']);
//                $record['skills'] = $em->getRepository("TruckeeMatchBundle:Skill")->findBy(['skill' => 'All']);
//            }
//            else {
//                $record['focuses'] = $search['focuses'] = (array_key_exists('focuses', $data)) ? $data['focuses'] : [];
//                $record['skills'] = $search['skills'] = (array_key_exists('skills', $data)) ? $data['skills'] : [];
//            }
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

        return $this->render('default/oppSearchForm.html.twig', array(
                    'form' => $form->createView(),
                    'title' => 'Search for opportunities',
        ));
    }
}
