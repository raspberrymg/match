<?php

namespace AppBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of SkillType
 *
 * @author George
 */
class SkillFieldType extends AbstractType {

    public function getName() {
        return 'skills';
    }

    public function getParent() {
        return 'entity';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
                    'class' => 'AppBundle:Skill',
                    'choice_label' => 'skill',
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => array('class' => 'list-inline'),
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                                ->orderBy('s.skill', 'ASC')
                                ->where("s.enabled = '1'")
                                ->andWhere("s.skill <> 'All'");
                        }
                ));
    }
}
