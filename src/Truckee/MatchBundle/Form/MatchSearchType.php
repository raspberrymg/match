<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\MatchSearchType

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Truckee\MatchBundle\Form\OrganizationSelectType;

/**
 * Description of MatchSearch
 *
 * @author George
 */
class MatchSearchType extends AbstractType
{

    private $tokenStorage;
    private $tool;

    public function __construct(TokenStorageInterface $tokenStorage, $tool)
    {
        $this->tokenStorage = $tokenStorage;
        $this->tool = $tool;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('organization', new OrganizationSelectType())
                ->add('Search', 'submit', array(
                    'attr' => array(
                        'class' => 'btn-xs',
                    )
                ))
        ;
        $user = $this->tokenStorage->getToken()->getUser();
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {

            $type = $this->tool->getUserType($user);
            $form = $event->getForm();
            if ('volunteer' === $type) {
                $form
                        ->add('focuses', 'focuses', array(
                            'mapped' => false,
                            'data' => $user->getFocuses(),
                        ))
                        ->add('skills', 'skills', array(
                            'mapped' => false,
                            'data' => $user->getSkills(),
                ));
                ;
            }
            else {
                $form
                        ->add('focuses', 'focuses', array(
                            'mapped' => false
                        ))
                        ->add('skills', 'skills', array(
                            'mapped' => false
                ));
            }
        });
    }

    public function getName()
    {
        return 'match_search';
    }
}
