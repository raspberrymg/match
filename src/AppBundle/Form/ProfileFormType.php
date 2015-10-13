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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileFormType extends AbstractType
{

    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('firstName', null, array(
                    'label' => 'First name: ',
                    'attr' => array(
                        'placeholder' => 'First name',
                    ),
                ))
                ->add('lastName', null, array(
                    'label' => 'Last name: ',
                    'attr' => array(
                        'placeholder' => 'Last name',
                    ),
                ))
                ->add('username', null, array(
                    'label' => 'form.username',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'placeholder' => 'Username',
                    ),
                ))
                ->add('email', 'email', array(
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'placeholder' => 'E-mail',
                    ),
                ))
                ->add('current_password', 'password', array(
                    'label' => 'form.current_password',
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                    'translation_domain' => 'FOSUserBundle',
                    'mapped' => false,
                    'constraints' => new UserPassword(['message' => 'Password incorrrect']),
                    'attr' => array(
                        'placeholder' => 'Current password',
                    ),
        ));
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $class = $this->class;
            $l = strrpos($class, "\\");
            $type = strtolower(substr($class, $l + 1));
            if ('volunteer' === $type) {
                $form = $event->getForm();
                $form->add('receiveEmail', 'checkbox', [
                            'label' => 'Check to receive e-mail, uncheck to stop',
                            'required' => '0',
                        ])
                        ->add('focuses', 'focuses')
                        ->add('skills', 'skills')
                ;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile',
        ));
    }

    public function getName()
    {
        return 'fos_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
                ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ;
    }
}
