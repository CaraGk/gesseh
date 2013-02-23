<?php
// src/Gesseh/UserBundle/Form/UserAdminType.php

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class UserAdminType extends BaseType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('email', 'email')
            ->add('plainPassword', 'repeated', array(
              'first_name' => 'password',
              'second_name' => 'confirm',
              'type' => 'password',
            ));
  }

  public function getName()
  {
    return 'gesseh_user_admin';
  }
}
