<?php

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EvaluationType extends AbstractType
{
  private $criterias;

  public function __construct($criterias)
  {
    $this->criterias = $criterias;
  }

  public function buildForm(FormBuilder $builder, array $options)
  {
    foreach ($this->criterias as $criteria) {
      if ($criteria->getType() == 1) {
        $builder->add('criteria_' . $criteria->getId(), 'choice', array(
          'choices'  => $this->getCriteriaChoiceOptions($criteria->getMore()),
          'required' => true,
          'multiple' => false,
          'expanded' => true,
          'label'    => $criteria->getName(),
        ));
      } elseif ($criteria->getType() == 2) {
        $builder->add('criteria_' . $criteria->getId(), 'textarea', array(
          'required'   => false,
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

    for($i = 0 ; $i < (int) $opt[0] ; $i ++) {
      $j = $i + 1;
      $choices[$j] = (string) $j;
      if ($label[$i] != null)
        $choices[$j] .= ' (' . $label[$i] . ')';
    }

    return $choices;
  }
}
