<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\PersonType


namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of PersonType.
 *
 * @author George
 */
class PersonDataType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text',
                array(
                'label' => 'First name: ',
                'attr' => array(
                    'placeholder' => 'First name',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('lastName', 'text',
                array(
                'label' => 'Last name: ',
                'attr' => array(
                    'placeholder' => 'Last name',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('email', 'email',
                array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'placeholder' => 'E-mail',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
            ), ))
            ->add('username', 'text',
                array(
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'placeholder' => 'Username',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
            ), ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
        ));
    }

    public function getName()
    {
        return 'person_data';
    }
}
