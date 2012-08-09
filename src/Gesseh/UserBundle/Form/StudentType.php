<?php
// src/Gesseh/UserBundle/Form/StudentType.php

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StudentType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('surname')
            ->add('name')
            ->add('phone')
            ->add('user', new UserAdminType('Gesseh\UserBundle\Entity\User'))
            ->add('grade');

    // Si la simulation est activée
    //if($this->getContainer()->get('kbd_parameters.manager')->findParamByName('simulation_active')->getValue()) {
    if(true) {
      $builder->add('ranking')
              ->add('graduate');
    }
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
