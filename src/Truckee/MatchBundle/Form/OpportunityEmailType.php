<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\OpportunityEmailType.php

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Description of Event
 *
 */
class OpportunityEmailType extends AbstractType
{
    private $oppName;
    private $orgName;
    private $email;
    private $id;

    public function __construct($oppName, $orgName, $email, $id)
    {
        $this->oppName = $oppName;
        $this->orgName = $orgName;
        $this->email   = $email;
        $this->id      = $id;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class,
                [
                'data' => $this->id
            ])
            ->add('to', TextType::class,
                [
                'data' => $this->orgName,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ])
            ->add('from', EmailType::class,
                [
                'data' => $this->email,
                'constraints' => [
                    new Email(["message" => "Not a valid e-mail address"]),
                    new NotBlank(["message" => "E-mail address is required"])
                ]
            ])
            ->add('subject', TextType::class,
                [
                'data' => $this->oppName.' opportunity',
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ])
            ->add('message', TextareaType::class,
                [
                'constraints' => [
                    new NotBlank(["message" => "Message is required"])
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'opp_email';
    }
}
