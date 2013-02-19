<?php
// src/Gesseh/UserBundle/Form/UserType.php

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class UserType extends BaseType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('email', 'email');
  }

  public function getName()
  {
    return 'gesseh_user';
  }
}
