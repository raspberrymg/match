<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\RegistrationFormType;

class RegistrationType extends RegistrationFormType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email', 'email', array(
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'placeholder' => 'E-mail',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
            )))
                ->add('username', null, array(
                    'label' => 'form.username',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'placeholder' => 'Username',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
            )))
                ->add('plainPassword', 'repeated', array(
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
                ->add('save', 'submit', array(
                    'label' => 'Save',
                    'attr' => array(
                        'class' => 'btn-xs btn-info',
                    )
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'registration',
        ));
    }

    public function getName()
    {
        return 'registration';
    }
}
