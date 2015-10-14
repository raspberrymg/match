<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\EventListener\RegistrationListener

namespace Truckee\MatchBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of RegistrationListener
 *
 * @author George
 */
class RegistrationListener implements EventSubscriberInterface
{

    private $em;
    private $mailer;
    private $tools;

    public function __construct(EntityManager $em, $messager, $tools)
    {
        $this->em = $em;
        $this->mailer = $messager;
        $this->tools = $tools;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    /**
     * Persist organization on staff registration success
     * @param \FOS\UserBundle\Event\FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();
        $user->setAddDate(new \DateTime());
        $type = $this->tools->getUserType($user);
        if ('staff' === $type) {
            $organization = $user->getOrganization();
            $organization->setTemp(true);
            $user->setOrganization($organization);
            $this->em->persist($organization);
            $user->addRole('ROLE_STAFF');
            $this->mailer->sendNewOrganization($organization);
        }
        if ('admin' === $type) {
            $user->addRole('ROLE_ADMIN');
        }
        if ('volunteer' === $type) {
            $user->setReceiveEmail(true);
            $user->setEnabled(true);
        }
    }
}
