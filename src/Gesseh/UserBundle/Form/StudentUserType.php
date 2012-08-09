<?php
// src/Gesseh/UserBundle/Form/StudentType.php

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StudentUserType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('surname')
            ->add('name')
            ->add('anonymous')
            ->add('phone')
            ->add('user', new UserAdminType('Gesseh\UserBundle\Entity\User'));
  }

  public function getName()
  {
    return 'gesseh_userbundle_studenttype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\UserBundle\Entity\Student'
    );
  }
}
