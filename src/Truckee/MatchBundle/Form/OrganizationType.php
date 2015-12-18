<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\OrganizationType

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class OrganizationType extends AbstractType
{
    private $focusRequired;

    public function __construct($focusRequired)
    {
        $this->focusRequired = $focusRequired;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', 'checkbox',
                array(
                'label' => 'Active: ',
            ))
            ->add('addDate', 'date')
            ->add('orgName', 'text',
                array(
                'label' => 'Name',
                'attr' => array(
                    'placeholder' => 'Name',
                    'onchange' => 'orgNameCheck()',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('address', 'text',
                array(
                'label' => 'Address',
                'attr' => array(
                    'placeholder' => 'Address',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('background', 'checkbox',
                [
                'label' => "Background check req'd",
            ])
            ->add('city', 'text',
                array(
                'label' => 'City',
                'attr' => array(
                    'placeholder' => 'City',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('state', 'text',
                array(
                'label' => 'State',
                'attr' => array(
                    'placeholder' => 'State',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('zip', 'text',
                array(
                'label' => 'Zip code',
                'attr' => array(
                    'placeholder' => 'Zip code',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('email', 'email',
                array(
                'label' => 'E-mail',
                'attr' => array(
                    'placeholder' => 'E-mail',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('areacode', 'number',
                array(
                'label' => 'Area code',
                'attr' => array(
                    'placeholder' => 'Area code',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('phone', 'text',
                array(
                'label' => 'Phone #',
                'attr' => array(
                    'placeholder' => 'Phone #',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('website', 'text',
                array(
                'label' => 'Website',
                'attr' => array(
                    'placeholder' => 'Website',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
                )
            )
            ->add('opportunities', 'collection',
                array(
                'type' => new OpportunityType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ))
            ->add('save', 'submit',
                array(
                'label' => 'Save organization',
                'attr' => array(
                    'class' => 'btn  btn-xs active btn-primary',
                ),
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
            $form = $event->getForm();
            if ($this->focusRequired) {
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
        return 'org';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Truckee\MatchBundle\Entity\Organization',
            'required' => false,
        ));
    }
}
