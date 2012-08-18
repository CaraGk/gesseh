<?php
// src/Gesseh/CoreBundle/Form/PlacementType.php

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PlacementType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('period')
            ->add('student', 'entity', array(
              'class'         => 'GessehUserBundle:Student',
              'query_builder' => function(\Gesseh\UserBundle\Entity\StudentRepository $er) { 
                return $er->createQueryBuilder('s')
                  ->addOrderBy('s.surname', 'ASC')
                  ->addOrderBy('s.name', 'ASC');
              },
            ))
            ->add('department');
  }

  public function getName()
  {
    return 'gesseh_corebundle_placementtype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\CoreBundle\Entity\Placement'
    );
  }
}
