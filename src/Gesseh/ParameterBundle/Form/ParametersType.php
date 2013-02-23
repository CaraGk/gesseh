<?php
// src/Gesseh/ParameterBundle/Form/ParametersType.php

namespace Gesseh\ParameterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ParametersType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    var_dump($options);
    foreach($options['data'] as $parameter) {
//      $builder->add($parameter->getName(), new ParameterType('Gesseh\ParameterBundle\Entity\Parameter'));
      $builder->add($parameter->getName(), new ParameterType($parameter));
    }
  }

  public function getName()
  {
    return 'gesseh_parameterbundle_parameterstype';
  }
}
