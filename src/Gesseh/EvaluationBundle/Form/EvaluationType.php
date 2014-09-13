<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Evaluationtype
 */
class EvaluationType extends AbstractType
{
  private $criterias;

  public function __construct($criterias)
  {
    $this->criterias = $criterias;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    foreach ($this->criterias as $criteria) {
      if ($criteria->getType() == 1) {
        $builder->add('criteria_' . $criteria->getId(), 'choice', array(
          'choices'  => $this->getCriteriaChoiceOptions($criteria->getMore()),
          'required' => $criteria->getRequired(),
          'multiple' => false,
          'expanded' => true,
          'label'    => $criteria->getName(),
        ));
      } elseif ($criteria->getType() == 2) {
        $builder->add('criteria_' . $criteria->getId(), 'textarea', array(
          'required'   => $criteria->getRequired(),
          'trim'       => true,
          'max_length' => 250,
          'label'      => $criteria->getName(),
        ));
      }
    }
  }

  public function getName()
  {
    return 'gesseh_simulationbundle_evaluationtype';
  }

  public function getCriteriaChoiceOptions($options)
  {
    $opt = explode("|", $options);
    $label = explode(",", $opt[1]);
    $choices = array();

    for ($i = 0 ; $i < (int) $opt[0] ; $i ++) {
      $j = $i + 1;
      $choices[$j] = (string) $j;
      if ($label[$i] != null)
        $choices[$j] .= ' (' . $label[$i] . ')';
    }

    return $choices;
  }
}
