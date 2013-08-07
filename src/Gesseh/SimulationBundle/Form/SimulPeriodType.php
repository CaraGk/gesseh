<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * SimulPeriodType
 */
class SimulPeriodType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('period')
            ->add('begin')
            ->add('end');
  }

  public function getName()
  {
    return 'gesseh_simulationbundle_simulperiodtype';
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\SimulationBundle\Entity\SimulPeriod',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
