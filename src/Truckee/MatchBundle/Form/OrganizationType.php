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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Count;

class OrganizationType extends AbstractType
{
    private $focusRequired;

    public function __construct($focusRequired)
    {
        $this->focusRequired = $focusRequired;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', CheckboxType::class,
                array(
                'label' => 'Active: ',
            ))
            ->add('addDate', DateType::class)
            ->add('orgName', TextType::class,
                array(
                'label' => 'Name',
                'attr' => array(
                    'placeholder' => 'Name',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('address', TextType::class,
                array(
                'label' => 'Address',
                'attr' => array(
                    'placeholder' => 'Address',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('background', CheckboxType::class,
                [
                'label' => "Background check req'd",
            ])
            ->add('city', TextType::class,
                array(
                'label' => 'City',
                'attr' => array(
                    'placeholder' => 'City',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('state', TextType::class,
                array(
                'label' => 'State',
                'attr' => array(
                    'placeholder' => 'State',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('zip', TextType::class,
                array(
                'label' => 'Zip code',
                'attr' => array(
                    'placeholder' => 'Zip code',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('email', EmailType::class,
                array(
                'label' => 'E-mail',
                'attr' => array(
                    'placeholder' => 'E-mail',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('areacode', NumberType::class,
                array(
                'label' => 'Area code',
                'attr' => array(
                    'placeholder' => 'Area code',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('phone', TextType::class,
                array(
                'label' => 'Phone #',
                'attr' => array(
                    'placeholder' => 'Phone #',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('website', TextType::class,
                array(
                'label' => 'Website',
                'attr' => array(
                    'placeholder' => 'Website',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
                )
            )
            ->add('opportunities', CollectionType::class,
                array(
                'type' => new OpportunityType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ))
            ->add('save',SubmitType::class, 
                array(
                'label' => 'Save organization',
                'attr' => array(
                    'class' => 'btn  btn-xs active btn-primary',
                ),
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
            $form = $event->getForm();
            if ($this->focusRequired) {
                $form->add('focuses', 'focuses',
                    array(
                    'constraints' => array(
                        new Count(array('min' => '1', 'minMessage' => 'At least one focus is required')),
                    ),
                ));
            }
        });
    }

    public function getName()
    {
        return 'org';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Truckee\MatchBundle\Entity\Organization',
            'required' => false,
        ));
    }
}
