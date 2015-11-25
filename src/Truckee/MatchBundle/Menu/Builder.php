<?php

/*
 * This file is part of the Truckee\Volunteer package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// src/Mana/ClientBundle/Menu/Builder.php


namespace Truckee\VolunteerBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $securityContext = $this->container->get('security.context');
        $user = (is_object($securityContext->getToken())) ? $securityContext->getToken()->getUser() : 'anon.';
        $type = null;

        if ('anon.' != $user) {
            $tool = $this->container->get('truckee.toolbox');
            $type = $tool->getUserType($user);
        }

        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav',
            ),
        ));

        $menu->addChild('Home', array(
            'route' => 'home',
        ));
        $menu->addChild('Volunteering', array(
            'route' => 'volunteer',
        ));
        $menu->addChild('Non-profits', array(
            'route' => 'nonprofits',
        ));
        $menu->addChild('About Us', array(
            'route' => 'about_us',
        ));
        $menu->addChild('Contact Us', array(
            'route' => 'contact_us',
        ));

        if ('anon.' === $user || 'sandbox' === $type) {
            $menu->addChild('Sign in', array(
                'route' => 'fos_user_security_login',
            ));
        } else {
            $menu->addChild('Sign out', array(
                'route' => 'fos_user_security_logout',
            ));
        }

        return $menu;
    }

    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav',
            ),
        ));
        $securityContext = $this->container->get('security.context');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $templates = $em->getRepository('TruckeeVolunteerBundle:Template')->getTemplates();

        $menu->addChild('Dashboard', array(
            'route' => 'dashboard',
        ));
        $menu->addChild('org_edit', array(
            'route' => 'org_select',
            'label' => 'Organizations',
        ));
        $menu->addChild('Volunteers', array(
            'route' => 'person_select',
            'routeParameters' => array('class' => 'volunteer'),
        ));

        if ($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $menu->addChild('Admin users')
                    ->setAttribute('dropdown', true);
            $menu['Admin users']->addChild('Add', [
                'route' => 'reg_admin',
                    ]
            );
            $menu['Admin users']->addChild('Lock/unlock status', [
                'route' => 'person_select',
                'routeParameters' => array('class' => 'admin'),
                    ]
            );
        }

        $menu->addChild('Edit personal data')
                ->setAttribute('dropdown', true);
        $menu['Edit personal data']->addChild('Edit profile', [
            'route' => 'fos_user_profile_edit',
                ]
        );
        $menu['Edit personal data']->addChild('Change password', [
            'route' => 'fos_user_change_password',
                ]
        );

        $menu->addChild('Templates')
                ->setAttribute('dropdown', true);
        foreach ($templates as $template) {
            $menu['Templates']->addChild($template->getDescription(), [
                'route' => 'template_edit',
                'routeParameters' => array('id' => $template->getId()),
            ]);
        }

        $menu->addChild('Criteria')
                ->setAttribute('dropdown', true);
        $menu['Criteria']->addChild('Focus', [
            'route' => 'focus_edit',
        ]);
        $menu['Criteria']->addChild('Skill', [
            'route' => 'skill_edit',
        ]);

        return $menu;
    }

    public function staffMenu(FactoryInterface $factory, array $options)
    {
        $securityContext = $this->container->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $orgId = $user->getOrganization()->getId();

        $orgActive = $user->getOrganization()->getActive();

        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav',
            ),
        ));

        $menu->addChild('Edit organization', array(
            'route' => 'org_edit',
            'routeParameters' => array('id' => $orgId),
        ));
        if ($orgActive) {
            $menu->addChild('Add opportunity', array(
                'route' => 'opp_new',
            ));
        }
        $menu->addChild('Add event', array(
            'route' => 'event_manage',
        ));
        $menu->addChild('Edit personal data')
                ->setAttribute('dropdown', true);
        $menu['Edit personal data']->addChild('Edit profile', [
            'route' => 'fos_user_profile_edit',
                ]
        );
        $menu['Edit personal data']->addChild('Change password', [
            'route' => 'fos_user_change_password',
                ]
        );

        return $menu;
    }

    public function volunteerMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav',
            ),
        ));

        $menu->addChild('Edit personal data')
                ->setAttribute('dropdown', true);
        $menu['Edit personal data']->addChild('Edit profile', [
            'route' => 'fos_user_profile_edit',
                ]
        );
        $menu['Edit personal data']->addChild('Change password', [
            'route' => 'fos_user_change_password',
                ]
        );
        $menu->addChild('Search', array(
            'route' => 'opp_search',
        ));

        return $menu;
    }

    public function sandboxMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav navbar-right',
            ),
        ));
        $menu->addChild('Sign out', array(
            'route' => 'fos_user_security_logout',
        ));

        return $menu;
    }

    public function vmMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav',
            ),
        ));
        $menu->addChild('Volunteer Volunteer Sandbox');

        return $menu;
    }
}
