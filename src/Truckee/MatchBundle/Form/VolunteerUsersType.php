<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\VolunteerUsersType

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * Description of VolunteerUsersType
 * 
 * Form type for admin edit of volunteers
 *
 * @author George
 */
class VolunteerUsersType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'entity',
                array(
                'label' => 'Volunteer',
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
                'class' => 'TruckeeMatchBundle:Volunteer',
                'choice_label' => 'nameLockStatus',
                'empty_value' => 'Select volunteer',
                'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('v')
                    ->orderBy('v.lastName, v.firstName', 'ASC')
                ;
            },
            ))
            ->add('Select', 'submit',
                array(
                'attr' => array(
                    'class' => 'btn btn-xs active',
                )
            ))
        ;
    }

    public function getName()
    {
        return 'vol_select';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
        ));
    }
}
