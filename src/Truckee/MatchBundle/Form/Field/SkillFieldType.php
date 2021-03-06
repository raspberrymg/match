<?php

namespace Truckee\MatchBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Truckee\MatchBundle\Entity\SkillRepository;

/**
 * Description of SkillType.
 *
 */
class SkillFieldType extends AbstractType
{
    private $repo;

    public function __construct(SkillRepository $repo)
    {
        $this->repo = $repo;
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
            'class' => 'TruckeeMatchBundle:Skill',
            'choice_label' => 'skill',
            'expanded' => true,
            'multiple' => true,
            'attr' => array('class' => 'list-inline'),
            'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('s')
                    ->orderBy('s.skill', 'ASC')
                    ->where("s.enabled = '1'")
                    ->andWhere("s.skill <> 'All'");
        },
            'label' => $this->isPopulated(),
        ));
    }

    private function isPopulated()
    {
        $populated = $this->repo->countSkills();

        return (1 >= $populated) ? 'Sign in as Admin; add skill critieria' : 'Skill criteria';
    }
}
