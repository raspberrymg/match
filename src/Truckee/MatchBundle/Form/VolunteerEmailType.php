<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\VolunteerEmailType

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Select volunteers for opportunity eblast
 *
 * @author George
 */
class VolunteerEmailType extends AbstractType
{

    private $idArray;

    public function __construct($idArray = null)
    {
        $this->idArray = $idArray;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('selectAll', 'checkbox', [
                    'mapped' => '0',
                    'label' => 'Select/unselect all',
                ])
                ->add('send', 'choice', array(
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $this->idArray,
                ))
                ->add('volunteers')
        ;
    }

    public function getName()
    {
        return 'vol_email';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'cascade_validation' => true,
            'required' => false,
        ));
    }
}
