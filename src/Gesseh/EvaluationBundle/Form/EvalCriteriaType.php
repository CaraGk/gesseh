<?php
// src/Gesseh/EvaluationBundle/Form/DepartmentType.php

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EvalCriteriaType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('rank')
            ->add('name')
            ->add('type', 'choice', array(
              'choices' => array(
                '1' => 'Bouton radio',
                '2' => 'Texte long',
              ),
              'required' => true,
              'multiple' => false,
              'expanded' => false,
            ))
            ->add('more')
    ;
  }

  public function getName()
  {
    return 'gesseh_evaluationbundle_evalcriteriatype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\EvaluationBundle\Entity\EvalCriteria'
    );
  }
}
