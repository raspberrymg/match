<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\StaffAddType.php

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * StaffAddType
 *
 */
class StaffAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personData', new PersonDataType(), array(
                'data_class' => 'Truckee\MatchBundle\Entity\Staff'
            ))
            ->add('registerPassword', new RegisterPasswordType(), array(
                'data_class' => 'Truckee\MatchBundle\Entity\Staff'
            ))
            ->add('save', 'submit',
                array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn-xs btn-info',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Truckee\MatchBundle\Entity\Staff',
        ));
    }

    public function getName()
    {
        return 'staff_add';
    }
    
}
