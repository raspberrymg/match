<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tools\UserTypeExtension.php

namespace Truckee\MatchBundle\Tools;

use Symfony\Component\Security\Core\SecurityContext;

/**
 * Creates Twig "filter" to allow use of user type (as defined in Person entity)
 *
 * @author George
 */
class UserTypeExtension extends \Twig_Extension
{

    private $context;

    public function __construct(SecurityContext $context, $tools)
    {
        $this->context = $context;
        $this->tools = $tools;
    }

    private function getUser()
    {
        $user = (is_object($this->context->getToken())) ? $this->context->getToken()->getUser() : 'anon.';
        return $user;
    }

    public function getType()
    {
        $user = $this->getUser();
        return $this->tools->getUserType($user);
    }

        public function getFilters()
    {
        return array(
            'userType' => new \Twig_Filter_Method($this, "getType"),
        );
    }

    public function getName()
    {
        return 'userType';
    }
}
