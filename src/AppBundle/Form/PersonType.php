<?php
/*
 * This file is part of the App package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//srcsrc\AppBundle\Form\PersonType

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\RegistrationType as BaseType;

/**
 * Description of PersonType
 * for adding staff by admin user
 *
 * @author George
 */
class PersonType extends BaseType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('firstName', null,
                array(
                'label' => 'First name: ',
                'attr' => array(
                    'placeholder' => 'First name',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                )
            ))
            ->add('lastName', null,
                array(
                'label' => 'Last name: ',
                'attr' => array(
                    'placeholder' => 'Last name',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                )
            ))
            ->add('save', 'submit',
                array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn-xs',
                )
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
            )))
            ->add('username', null,
                array(
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'placeholder' => 'Username',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
            )))
            ->add('plainPassword', 'repeated',
                array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password',
                    'attr' => array(
                        'placeholder' => 'Password',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    )),
                'second_options' => array('label' => 'form.password_confirmation',
                    'attr' => array(
                        'placeholder' => 'Confirm password',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    )),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('save', 'submit',
                array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn-xs btn-info',
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
            'data_class' => 'AppBundle\Entity\Person',
            'intention' => 'registration',
            'required' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'person_registration';
    }
}
