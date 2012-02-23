<?php
// src/Gesseh/CoreBundle/Form/DepartmentType.php

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DepartmentType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('head')
      ->add('sector')
      ->add('description');
  }

  public function getName()
  {
    return 'gesseh_corebundle_departmenttype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\CoreBundle\Entity\Department'
    );
  }
}
