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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Description of PersonType.
 *
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
            ->add('firstName', TextType::class,
                array(
                'label' => 'First name: ',
                'attr' => array(
                    'placeholder' => 'First name',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('lastName', TextType::class,
                array(
                'label' => 'Last name: ',
                'attr' => array(
                    'placeholder' => 'Last name',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
            ))
            ->add('email', EmailType::class,
                array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'placeholder' => 'E-mail',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),))
            ->add('username', TextType::class,
                array(
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'placeholder' => 'Username',
                ),
                'label_attr' => array(
                    'class' => 'sr-only',
                ),))
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
