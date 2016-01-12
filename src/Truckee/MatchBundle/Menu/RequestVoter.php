<?php
/*
 * This file is part of the Truckee\Volunteer package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\VolunteerBundle\Menu\RequestVoter.php

namespace Truckee\MatchBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Description of RequestVoter.
 *
 */
class RequestVoter implements VoterInterface
{
    private $request;

    public function __construct(RequestStack $request_stack)
    {
        $this->request = $request_stack->getCurrentRequest();
    }

    public function matchItem(ItemInterface $item)
    {
        if (NULL === $this->request) {
            return null;
        }
        if ($item->getUri() === $this->request->getRequestUri()) {
            // URL's completely match
            return true;
        } else if ($item->getUri() !== $this->request->getBaseUrl() . '/' && (substr($this->request->getRequestUri(), 0,
                strlen($item->getUri())) === $item->getUri())) {
            // URL isn't just "/" and the first part of the URL match
            return true;
        }
        return null;
    }
}
