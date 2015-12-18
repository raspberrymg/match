<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Controller\EventController

namespace Truckee\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Truckee\MatchBundle\Form\EventType;
use Truckee\MatchBundle\Entity\Event;
use Doctrine\Common\Collections\Criteria;

/**
 *
 * @Route("/event")
 */
class EventController extends Controller
{

    /**
     * @Route("/manage/{id}", name="event_manage")
     * @Security("has_role('ROLE_STAFF')")
     * @Template("Event/manage.html.twig")
     */
    public function manageAction(Request $request, $id = null)
    {
        $securityContext = $this->container->get('security.context');
        if (!$securityContext->isGranted('ROLE_STAFF')) {

            return $this->redirect($this->generateUrl('home'));
        }
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if (null === $id) {
            $event = new Event();
            $message = "Event added";
        }
        else {
            $event = $em->getRepository("TruckeeMatchBundle:Event")->find($id);
            if (!$event) {
                throw $this->createNotFoundException('Unable to find Event');
            }
            $message = "Event updated";
        }
        $form = $this->createForm(new EventType(), $event);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $owner = $event->getOwner();
            if (empty($owner)) {
                $event->setOwner($user);
            }
            $em->persist($event);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success($message);

            return $this->redirect($this->generateUrl('home'));
        }

        return array(
            'form' => $form->createView(),
            'event' => $event,
        );
    }

    /**
     * @Template("Event/sidebarEvents.html.twig")
     * @return type
     */
    public function sidebarEventsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $eventRepository = $em->getRepository("TruckeeMatchBundle:Event");

        $criteria = new Criteria();
        $criteria->where($criteria->expr()->gte('eventdate', new \DateTime()));
        $criteria->orderBy(['eventdate' => 'ASC']);
        $criteria->setMaxResults(5);

        return array(
            'events' => $eventRepository->matching($criteria),
        );
    }
}
