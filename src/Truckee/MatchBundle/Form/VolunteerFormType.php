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
use Truckee\MatchBundle\Form\PersonType as BaseType;

class VolunteerFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('skills', 'skills')
            ->add('focuses', 'focuses')
            ->add('receiveEmail', 'checkbox',
                [
                'label' => 'Check to receive e-mail, uncheck to stop',
            ])
        ;
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