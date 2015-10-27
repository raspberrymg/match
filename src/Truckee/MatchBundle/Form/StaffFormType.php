<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\StaffFormType

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Truckee\MatchBundle\Form\PersonType as BaseType;
use Truckee\MatchBundle\Form\OrganizationType;

class StaffFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
                ->add('organization', new OrganizationType($this->options))
        ;
    }

    public function getName()
    {
        return 'staff_registration';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Truckee\MatchBundle\Entity\Staff',
            'error_bubbling' => false,
            'required' => false,
        ));
    }
}
