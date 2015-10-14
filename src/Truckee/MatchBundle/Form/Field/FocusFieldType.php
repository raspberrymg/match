<?php

namespace Truckee\MatchBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Truckee\MatchBundle\Entity\FocusRepository;

/**
 * Description of FocusFieldType
 *
 * @author George
 */
class FocusFieldType extends AbstractType {

    private $repo;

    public function __construct(FocusRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getName() {
        return 'focuses';
    }

    public function getParent() {
        return 'entity';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            array(
                    'class' => 'TruckeeMatchBundle:Focus',
                    'choice_label' => 'focus',
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => array('class' => 'list-inline'),
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('f')
                                ->orderBy('f.focus', 'ASC')
                                ->where("f.enabled = '1'")
                                ->andWhere("f.focus <> 'All'");
                        },
                    'label' => $this->isPopulated()
                )
                            )
        ;
    }

    private function isPopulated()
    {
        $populated = $this->repo->isFocusPopulated();

        return ("0" === $populated) ? 'Sign in as Admin; add focus critieria' : 'Focus criteria';
    }
}
