<?php

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SectorRuleType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('sector')
            ->add('grade')
            ->add('relation')
    ;
  }

  public function getName()
  {
    return 'gesseh_simulationbundle_sectorruletype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\SimulationBundle\Entity\SectorRule',
    );
  }
}