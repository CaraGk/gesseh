<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * SectorRuleType
 */
class SectorRuleType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder->add('grade')
              ->add('relation', 'choice', array(
                  'choices' => array('NOT' => 'ne doit pas faire de stage de', 'FULL' => 'doit compléter les stage de'),
                  'required' => true,
                  'multiple' => false,
                  'expanded' => false,
              ))
              ->add('sector')
              ->add('Enregistrer', 'submit')
      ;
  }

  public function getName()
  {
    return 'gesseh_simulationbundle_sectorruletype';
  }

  public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\SimulationBundle\Entity\SectorRule',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
