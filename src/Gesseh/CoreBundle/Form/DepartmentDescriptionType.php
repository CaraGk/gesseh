<?php
// src/Gesseh/CoreBundle/Form/DepartmentDescriptionType.php

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DepartmentDescriptionType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('description', 'textarea', array(
      'attr' => array(
        'class'      => 'tinymce',
        'data-theme' => 'medium'
      ),
    ));
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
