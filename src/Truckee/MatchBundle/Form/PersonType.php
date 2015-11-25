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

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PUGX\MultiUserBundle\Form\RegistrationFormType as BaseType;

/**
 * Description of PersonType.
 *
 * @author George
 */
class PersonType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('personData', new PersonDataType(), array(
                'data_class' => 'Truckee\MatchBundle\Entity\Person',
            ))
            ->add('registerPassword', new RegisterPasswordType(), array(
                'data_class' => 'Truckee\MatchBundle\Entity\Person',
            ))
            ->add('save', 'submit',
                array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn-xs btn-info',
                ),
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
