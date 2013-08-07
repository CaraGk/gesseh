<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * HospitalType
 */
class HospitalType extends AbstractType
{
  private $testSimulActive;

  public function __construct($testSimulActive)
  {
    $this->testSimulActive = $testSimulActive;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('address')
      ->add('web')
      ->add('phone')
      ->add('departments', 'collection', array(
        'type' => new DepartmentType($this->testSimulActive),
        'allow_add' => true,
        'allow_delete' => true,
        'prototype' => true,
        'by_reference' => false,
      ));
  }

  public function getName()
  {
    return 'gesseh_corebundle_hospitaltype';
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\CoreBundle\Entity\Hospital',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
