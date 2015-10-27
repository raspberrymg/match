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
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('active', 'checkbox', array(
                    'label' => 'Active: ',
                ))
                ->add('orgName', null, array(
                    'label' => 'Name',
                    'attr' => array(
                        'size' => 40,
                        'placeholder' => 'Name',
                        'onchange' => 'orgNameCheck()',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('address', null, array(
                    'label' => 'Address',
                    'attr' => array(
                        'size' => 40,
                        'placeholder' => 'Address',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('background', 'checkbox', [
                    'label' => "Background check req'd",
                ])
                ->add('city', null, array(
                    'label' => 'City',
                    'attr' => array(
                        'placeholder' => 'City',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('state', null, array(
                    'label' => 'State',
                    'attr' => array(
                        'size' => 3,
                        'placeholder' => 'State',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('zip', null, array(
                    'label' => 'Zip code',
                    'attr' => array(
                        'size' => 9,
                        'placeholder' => 'Zip code',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('email', 'email', array(
                    'label' => 'E-mail',
                    'attr' => array(
                        'size' => 40,
                        'placeholder' => 'E-mail',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('areacode', 'number', array(
                    'label' => 'Area code',
                    'attr' => array(
                        'size' => 7,
                        'placeholder' => 'Area code',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('phone', null, array(
                    'label' => 'Phone #',
                    'attr' => array(
                        'size' => 12,
                        'placeholder' => 'Phone #',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                
                ->add('website', 'text', array(
                    'label' => 'Website',
                    'attr' => array(
                        'size' => 40,
                        'placeholder' => 'Website',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                )
                        )
                ->add('opportunities', 'collection', array(
                    'type' => new OpportunityType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'prototype' => true,
                ))
                ->add('focuses', 'focuses')
                ->add('save', 'submit', array(
                    'label' => 'Save organization',
                    'attr' => array(
                        'class' => 'btn  btn-xs active btn-primary',
                    )
                ))
        ;
    }

    public function getName() {
        return 'org';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Truckee\MatchBundle\Entity\Organization',
            'cascade_validation' => true,
            'required' => false,
        ));
    }
}
