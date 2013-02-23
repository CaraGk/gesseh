<?php

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EvalSectorType extends AbstractType
{
  private $exclude_sectors;

  public function __construct($exclude_sectors)
  {
    $this->exclude_sectors = $exclude_sectors;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $exclude_sectors = $this->exclude_sectors;

    $builder
      ->add('sector', 'entity', array(
        'class'         => 'GessehCoreBundle:Sector',
        'query_builder' => function(\Gesseh\CoreBundle\Entity\SectorRepository $er) use ($exclude_sectors) { return $er->listOtherSectors($exclude_sectors); },
      ))
      ->add('form')
    ;
  }

  public function getName()
  {
    return 'gesseh_evaluationbundle_evalsectortype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\EvaluationBundle\Entity\EvalSector',
    );
  }
}
