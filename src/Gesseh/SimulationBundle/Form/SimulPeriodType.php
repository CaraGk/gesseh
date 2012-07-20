<?php

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SimulPeriodType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('period')
            ->add('begin')
            ->add('end');
  }

  public function getName()
  {
    return 'gesseh_simulationbundle_simulperiodtype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\SimulationBundle\Entity\SimulPeriod'
    );
  }
}
