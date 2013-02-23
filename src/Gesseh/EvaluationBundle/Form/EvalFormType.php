<?php

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EvalFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('criterias', 'collection', array(
        'type' => new EvalCriteriaType(),
        'allow_add' => true,
        'allow_delete' => true,
        'prototype' => true,
        'by_reference' => false,
      ));
  }

  public function getName()
  {
    return 'gesseh_evaluationbundle_evalformtype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\EvaluationBundle\Entity\EvalForm',
    );
  }
}
