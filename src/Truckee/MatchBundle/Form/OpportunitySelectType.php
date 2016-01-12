<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\OpportunitySelectType.php

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * OpportunitySelectType
 *
 */
class OpportunitySelectType extends AbstractType
{
    private $orgId;

    public function __construct($orgId)
    {
        $this->orgId = $orgId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('opportunity', EntityType::class,
                array(
                'class' => 'TruckeeMatchBundle:Opportunity',
                'choice_label' => 'oppName',
                'label' => '',
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('o')
                    ->where("o.active = '1'")
                    ->andWhere("o.organization = $this->orgId");
            },
                'empty_value' => 'Select opportunity',)
            )
            ->add('save', SubmitType::class,
                array(
                'attr' => array(
                    'class' => "btn  btn-xs active btn-primary",
                    'label' => 'Select opportunity'
                )
            ))
        ;
    }

    public function getName()
    {
        return 'opp_select';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
        ));
    }
}
