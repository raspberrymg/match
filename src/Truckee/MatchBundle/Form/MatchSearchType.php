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

/**
 * Description of MatchSearch.
 *
 * @author George
 */
class MatchSearchType extends AbstractType
{
    private $userOptions;
    private $user;

    public function __construct($user, $userOptions)
    {
        $this->userOptions = $userOptions;
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('organization', new OrganizationSelectType())
            ->add('Search', 'submit',
                array(
                'attr' => array(
                    'class' => 'btn-xs',
                ),
            ))
        ;

        $user = $this->user;
        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($user) {

            $form = $event->getForm();
            $focusOptions = $skillOptions = array('mapped' => false);
            $userType = ('anon.' != $user) ? $user->getUserType() : array();

            if ($this->userOptions['focus_required']) {
                $focusOptions['data'] = ('volunteer' === $userType) ? $user->getFocuses()
                        : null;
                $form->add('focuses', 'focuses', $focusOptions);
            }
            if ($this->userOptions['skill_required']) {
                $skillOptions['data'] = ('volunteer' === $userType) ? $user->getSkills()
                        : null;
                $form->add('skills', 'skills', $skillOptions);
            }
        });
    }

    public function getName()
    {
        return 'match_search';
    }
}
