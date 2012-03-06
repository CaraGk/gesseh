<?php
// src/Gesseh/CoreBundle/Form/PlacementType.php

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PlacementType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('period')
            ->add('student')
            ->add('department');
  }

  public function getName()
  {
    return 'gesseh_corebundle_placementtype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\CoreBundle\Entity\Placement'
    );
  }
}
