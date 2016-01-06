<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\EventType.php

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Description of Event
 *
 */
class EventType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('event', TextareaType::class, array(
                    'label' => 'Event',
                    'attr' => array(
                        'placeholder' => 'Event',
                        'cols' => '40',
                        'rows' => '1',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('location', TextType::class, array(
                    'label' => 'Location',
                    'attr' => array(
                        'placeholder' => 'Location',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('starttime', TextType::class, array(
                    'label' => 'Start time: ',
                    'attr' => array(
                        'placeholder' => 'Start time (hh:mm AM/PM)',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('eventdate', DateType::class, array(
                    'widget' => 'single_text',
                    'format' => 'M/d/y',
                    'label' => 'Date',
                    'attr' => array(
                        'placeholder' => 'Event date (m/d/y)',
                       
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('submit',SubmitType::class,  array(
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
            'data_class' => 'Truckee\MatchBundle\Entity\Event',
            'required' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'event';
    }
}
