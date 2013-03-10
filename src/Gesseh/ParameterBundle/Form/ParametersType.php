<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\ParameterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * ParametersType
 */
class ParametersType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    var_dump($options);
    foreach($options['data'] as $parameter) {
//      $builder->add($parameter->getName(), new ParameterType('Gesseh\ParameterBundle\Entity\Parameter'));
      $builder->add($parameter->getName(), new ParameterType($parameter));
    }
  }

  public function getName()
  {
    return 'gesseh_parameterbundle_parameterstype';
  }
}
