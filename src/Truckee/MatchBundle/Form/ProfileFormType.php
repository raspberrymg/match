<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use PUGX\MultiUserBundle\Form\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personData', new PersonDataType(), array(
                'data_class' => 'Truckee\MatchBundle\Entity\Person',
            ))
            ->add('current_password', 'password',
                array(
                'label' => 'form.current_password',
                'label_attr' => array(
                    'class' => 'sr-only',
                ),
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => new UserPassword(['message' => 'Password incorrrect']),
                'attr' => array(
                    'placeholder' => 'Current password',
                ), ))
            ->add('save', 'submit',
                array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn-xs btn-info',
                ),
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
            $class = $this->class;
            $l = strrpos($class, '\\');
            $type = strtolower(substr($class, $l + 1));
            if ('volunteer' === $type) {
                $form = $event->getForm();
                $form->add('receiveEmail', 'checkbox',
                    [
                    'label' => 'Check to receive e-mail, uncheck to stop',
                    'required' => '0',
                ]);
                if ($this->options['skill_required']) {
                    $form->add('skills', 'skills');
                };
                if (null != $this->options && array_key_exists('focus_required',
                        $this->options) && $this->options['focus_required']) {
                    $form->add('focuses', 'focuses');
                };
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'profile',
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
//    protected function buildUserForm(FormBuilderInterface $builder,
//                                     array $options)
//    {
//        $builder
//            ->add('username', 'text',
//                array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
//            ->add('email', 'email',
//                array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
//        ;
//    }
}
