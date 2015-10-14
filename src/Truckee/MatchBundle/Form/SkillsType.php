<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\SkillsType.php

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Truckee\MatchBundle\Form\SkillType;

/**
 * Description of SkillType
 *
 * @author George
 */
class SkillsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('skills', 'collection', ['type' => new SkillType(),
                    ])
                ->add('save', 'submit', array(
                    'label' => 'Save',
                    'attr' => array(
                        'class' => 'btn-xs',
                    )
                ))
            ;
    }

    public function getName()
    {
        return 'skills';
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
}
