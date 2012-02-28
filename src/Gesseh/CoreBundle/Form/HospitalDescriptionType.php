<?php

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class HospitalDescriptionType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('description');
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
