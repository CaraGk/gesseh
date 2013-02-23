<?php

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SectorRuleType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('sector')
            ->add('grade')
            ->add('relation', 'choice', array(
              'choices' => array('NOT' => 'ne doit pas faire de stage de', 'FULL' => 'doit compléter les stage de'),
              'required' => true,
              'multiple' => false,
              'expanded' => false,
            ))
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
