<?php

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EvalSectorType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder
      ->add('sector')
      ->add('form')
    ;
  }

  public function getName()
  {
    return 'gesseh_evaluationbundle_evalsectortype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\EvaluationBundle\Entity\EvalSector',
    );
  }
}
