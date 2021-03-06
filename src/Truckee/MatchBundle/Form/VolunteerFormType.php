<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\VolunteerFormType

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Count;
use Truckee\MatchBundle\Form\PersonType as BaseType;

class VolunteerFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('receiveEmail', CheckboxType::class,
                [
                'label' => 'Check to receive e-mail, uncheck to stop',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
            $form = $event->getForm();
            if (null != $this->options && array_key_exists('skill_required', $this->options) && $this->options['skill_required']) {
                $form->add('skills', 'skills',
                    array(
                    'constraints' => array(
                        new Count(array('min' => '1', 'minMessage' => 'At least one skill is required')),
                    ),
                ));
            }
            if (null != $this->options && array_key_exists('focus_required', $this->options) && $this->options['focus_required']) {
                $form->add('focuses', 'focuses',
                    array(
                    'constraints' => array(
                        new Count(array('min' => '1', 'minMessage' => 'At least one focus is required')),
                    ),
                ));
            }
        });
    }

    public function getName()
    {
        return 'volunteer_registration';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Truckee\MatchBundle\Entity\Person',
            'required' => false,
        ));
    }
}
