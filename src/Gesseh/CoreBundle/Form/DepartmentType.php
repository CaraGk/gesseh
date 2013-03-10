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
 * DepartmentType
 */
class DepartmentType extends AbstractType
{
  private $testSimulActive;

  public function __construct($testSimulActive)
  {
    $this->testSimulActive = $testSimulActive;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('name')
            ->add('head')
            ->add('sector');

    // Si les simulations sont activées
    if ($this->testSimulActive == true)
      $builder->add('number');
  }

  public function getName()
  {
    return 'gesseh_corebundle_departmenttype';
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'Gesseh\CoreBundle\Entity\Department'
    );
  }
}
