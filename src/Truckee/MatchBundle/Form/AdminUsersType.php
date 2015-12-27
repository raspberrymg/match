<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\AdminUsersType

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * Description of AdminUsersType
 *
 */
class AdminUsersType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('user', 'entity', array(
                    'label' => 'Admin user',
                    'class' => 'TruckeeMatchBundle:Person',
                    'choice_label' => 'nameLockStatus',
                    'empty_value' => 'Select admin user',
                    'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('a')
                        ->where('a.roles LIKE :role')
                        ->setParameter('role', '%ROLE_ADMIN%')
                        ->orderBy('a.lastName, a.firstName', 'ASC')
                ;
            },
                ))
                ->add('Select', 'submit', array(
                    'attr' => array(
                        'class' => 'btn btn-xs active',
                    )
                ))
        ;
    }

    public function getName()
    {
        return 'admin_select';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
        ));
    }
}
