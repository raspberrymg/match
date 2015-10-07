<?php

namespace AppBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;

/**
 * Description of SkillType
 *
 * @author George
 */
class SkillFieldType extends AbstractType
{

     private $em;

    public function __construct(EntityManager $em)
{
    $this->em = $em;
}

    public function getName()
    {
        return 'skills';
    }

    public function getParent()
    {
        return 'entity';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
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
            ,
            'label' => $this->skillsExist()
        ));
    }

    private function skillsExist()
    {
        $qb = $this->em->createQuery(
                "SELECT s FROM AppBundle:Skill s "
                ."WHERE s.enabled = '1' AND s.skill <> 'All'"
            )->getResult()
        ;

        return (empty($qb)) ? 'Sign in as Admin; add skill critieria' : 'Skill criteria';
    }
}
