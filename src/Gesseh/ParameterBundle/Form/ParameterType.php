<?php
// src/Gesseh/ParameterBundle/Form/ParameterType.php

namespace Gesseh\ParameterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ParameterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('value');
  }

  public function getName()
  {
    return 'gesseh_parameterbundle_parametertype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\ParameterBundle\Entity\Parameter'
    );
  }
}
