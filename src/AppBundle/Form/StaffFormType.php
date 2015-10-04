<?php

/*
 * This file is part of the App package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\StaffFormType

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\PersonType as BaseType;
use AppBundle\Form\OrganizationType;

class StaffFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
                ->add('organization', new OrganizationType())
        ;
    }

    public function getName()
    {
        return 'staff_registration';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Staff',
            'error_bubbling' => false,
            'required' => false,
        ));
    }
}
