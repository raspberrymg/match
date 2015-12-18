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

/**
 * Description of Event
 *
 * @author George
 */
class EventType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('event', 'textarea', array(
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
                ->add('location', 'text', array(
                    'label' => 'Location',
                    'attr' => array(
                        'placeholder' => 'Location',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('starttime', 'text', array(
                    'label' => 'Start time: ',
                    'attr' => array(
                        'placeholder' => 'Start time (hh:mm AM/PM)',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                ))
                ->add('eventdate', 'date', array(
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
                ->add('submit', 'submit', array(
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
