<?php

namespace AppBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;

/**
 * Description of FocusFieldType
 *
 * @author George
 */
class FocusFieldType extends AbstractType {

     private $em;

    public function __construct(EntityManager $em)
{
    $this->em = $em;
}

    public function getName() {
        return 'focuses';
    }

    public function getParent() {
        return 'entity';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
//            $this->fociExist()
            array(
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
                        },
                    'label' => $this->fociExist()
                )
                            )
        ;
    }

    private function fociExist()
    {
        $qb = $this->em->createQuery(
            "SELECT f FROM AppBundle:Focus f "
            . "WHERE f.enabled = '1' AND f.focus <> 'All'"
            )->getResult()
            ;

        return (empty($qb)) ? 'Sign in as Admin; add focus critieria' : 'Focus criteria';
    }
}
