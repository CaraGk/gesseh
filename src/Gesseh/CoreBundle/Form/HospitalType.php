<?php

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class HospitalType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('address')
      ->add('web')
      ->add('phone')
      ->add('description')
      ->add('departments', 'collection', array(
        'type' => new DepartmentType(),
        'allow_add' => true,
        'allow_delete' => true,
        'prototype' => true,
      ));
  }

  public function getName()
  {
    return 'gesseh_corebundle_hospitaltype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\CoreBundle\Entity\Hospital',
    );
  }
}
