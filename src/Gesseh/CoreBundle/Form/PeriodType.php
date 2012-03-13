<?php
// src/Gesseh/CoreBundle/Form/PeriodType.php

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PeriodType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('begin')
            ->add('end');
  }

  public function getName()
  {
    return 'gesseh_corebundle_periodtype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\CoreBundle\Entity\Period'
    );
  }
}
