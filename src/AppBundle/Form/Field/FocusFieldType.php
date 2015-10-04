<?php

namespace AppBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of FocusFieldType
 *
 * @author George
 */
class FocusFieldType extends AbstractType {

    public function getName() {
        return 'focuses';
    }

    public function getParent() {
        return 'entity';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
                    'class' => 'AppBundle:Focus',
                    'choice_label' => 'focus',
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => array('class' => 'list-inline'),
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('f')
                                ->orderBy('f.focus', 'ASC')
                                ->where("f.enabled = '1'")
                                ->andWhere("f.focus <> 'All'");
                        }
                ));
    }
}
