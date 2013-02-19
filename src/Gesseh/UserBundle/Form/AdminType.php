<?php
// src/Gesseh/UserBundle/Form/AdminType.php

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType,
  Symfony\Component\Form\FormBuilder;

class AdminType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('surname')
            ->add('name')
            ->add('phone')
            ->add('user', new UserAdminType('Gesseh\UserBundle\Entity\User'))
            ->add('grade');
  }

  public function getName()
  {
    return 'gesseh_userbundle_admintype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\UserBundle\Entity\Student'
    );
  }
}
