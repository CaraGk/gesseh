<?php

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class HospitalDescriptionType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('description', 'textarea', array(
      'attr' => array(
        'class'      => 'tinymce',
        'data-theme' => 'medium',
      ),
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
