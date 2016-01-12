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
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Description of PersonType.
 *
 */
class RegisterPasswordType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class,
                array(
                'type' => PasswordType::class,
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password',
                    'attr' => array(
                        'placeholder' => 'Password',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),),
                'second_options' => array('label' => 'form.password_confirmation',
                    'attr' => array(
                        'placeholder' => 'Confirm password',
                    ),
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
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
        return 'register_password';
    }
}
