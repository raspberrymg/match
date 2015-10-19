<?php

namespace Truckee\MatchBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
