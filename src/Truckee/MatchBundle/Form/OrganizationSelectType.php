<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Form\OrganizationSelectType

namespace Truckee\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * Description of OrganizationSelectType
 *
 * @author George
 */
class OrganizationSelectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('organization', 'entity', [
                    'class' => 'Truckee\MatchBundle\Entity\Organization',
                    'choice_label' => 'orgName',
                    'label_attr' => array(
                        'class' => 'sr-only',
                    ),
                    'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('o')
                        ->where("o.temp = '0'");
            },
                    'empty_value' => 'Select organization']
                )
        ;
    }

    public function getName()
    {
        return 'org_select';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'cascade_validation' => true,
            'required' => false,
        ));
    }
}
