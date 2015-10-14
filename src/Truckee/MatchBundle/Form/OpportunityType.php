<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpportunityType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('active', 'checkbox', array(
                    'label' => 'Active'
                ))
                ->add('oppName', 'text', array(
                    'label' => 'Name',
                    'attr' => array(
                        'size' => 40,
                        'placeholder' => 'Name',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('description', 'textarea', array(
                    'label' => 'Description',
                    'attr' => array(
                        'placeholder' => 'Description',
                        'cols' => '60',
                        'rows' => '2',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('minage', 'choice', array(
                    'choices' => array(
                        '' => 'Minimum age',
                        '5' => '5',
                        '12' => '12',
                        '18' => '18',
                        '21' => '21',
                        '55' => '55'
                    ),
                    'label' => 'Minimum age: ',
                    'attr' => array(
                        'placeholder' => 'Minimum age',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('groupOk', 'checkbox', array(
                    'label' => 'Group OK'
                ))
                ->add('expireDate', 'date', array(
                    'widget' => 'single_text',
                    'format' => 'M/d/y',
                    'label' => 'Expiration date: ',
                    'attr' => array(
                        'placeholder' => 'Expiration date (m/d/y)',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    )
                ))
                ->add('skills', 'skills')
                ->add('save', 'submit', array(
                    'label' => 'Save opportunity',
                    'attr' => array(
                        'class' => 'btn-xs',
                    )
                ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Truckee\MatchBundle\Entity\Opportunity',
            'required' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'opportunity';
    }
}
