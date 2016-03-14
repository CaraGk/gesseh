<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
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
  private $eval_forms;

  public function __construct(array $eval_forms)
  {
    $this->eval_forms = $eval_forms;
  }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->eval_forms as $eval_form)
        {
            foreach ($eval_form->getCriterias() as $criteria) {
                if ($criteria->isPrivate() == true) {
                    $label = $criteria->getName() . " (réservée aux étudiants seuls)";
                    $class = 'private';
                } else {
                    $label = $criteria->getName() . " (peut-être transmise aux enseignants)";
                    $class = 'public';
                }

                if ($criteria->getType() == 1) {
                    $builder->add('criteria_' . $criteria->getId(), 'choice', array(
                        'choices'    => $this->getCriteriaSubjectiveChoiceOptions($criteria->getMore()),
                        'required'   => $criteria->isRequired(),
                        'multiple'   => false,
                        'expanded'   => true,
                        'label'      => $label,
                        'label_attr' => array('class' => $class),
                    ));
                } elseif ($criteria->getType() == 2) {
                    $builder->add('criteria_' . $criteria->getId(), 'textarea', array(
                        'required'   => $criteria->isRequired(),
                        'trim'       => true,
                        'max_length' => 250,
                        'label'      => $label,
                        'label_attr' => array('class' => $class),
                    ));
                } elseif ($criteria->getType() == 3) {
                    $builder->add('criteria_' . $criteria->getId(), 'choice', array(
                        'choices'    => $this->getCriteriaChoiceOptions($criteria->getMore()),
                        'required'   => $criteria->isRequired(),
                        'multiple'   => true,
                        'expanded'   => true,
                        'label'      => $label,
                        'label_attr' => array('class' => $class),
                    ));
                } elseif ($criteria->getType() == 4) {
                    $builder->add('criteria_' . $criteria->getId(), 'integer', array(
                        'precision'  => $criteria->getMore(),
                        'required'   => $criteria->isRequired(),
                        'label'      => $label,
                        'label_attr' => array('class' => $class),
                    ));
                } elseif ($criteria->getType() == 5) {
                    $builder->add('criteria_' . $criteria->getId(), 'choice', array(
                        'choices'    => $this->getCriteriaChoiceOptions($criteria->getMore(), array(0)),
                        'required'   => $criteria->isRequired(),
                        'multiple'   => false,
                        'expanded'   => true,
                        'label'      => $label,
                        'label_attr' => array('class' => $class),
                    ));
                } elseif ($criteria->getType() == 6) {
                    $builder->add('criteria_' . $criteria->getId(), 'time', array(
                        'input'        => 'string',
                        'widget'       => 'single_text',
                        'with_seconds' => false,
                        'required'     => $criteria->isRequired(),
                        'label'        => $label,
                        'label_attr'   => array('class' => $class),
                    ));
                } elseif ($criteria->getType() == 7) {
                    $legend = explode('|', $criteria->getMore());
                    $label .= ' (' . $legend[0] . ' -> ' . $legend[1] . ')';
                    $builder->add('criteria_' . $criteria->getId(), 'range', array(
                        'required'   => $criteria->isRequired(),
                        'label'      => $label,
                        'label_attr' => array('class' => $class),
                        'attr'       => array('min' => 0, 'max' => 100,),
                    ));
                }
            }
        }
        $builder->add('Enregister', 'submit');
    }

  public function getName()
  {
    return 'gesseh_evaluationbundle_evaluationtype';
  }

  public function getCriteriaSubjectiveChoiceOptions($options)
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

    public function getCriteriaChoiceOptions($options, $except = array())
    {
        $opt = explode("|", $options);
        $choice = array();
        foreach($opt as $key => $value) {
            if (!in_array($key, $except)) {
                $choice[$value] = $value;
            }
        }
        return $choice;
  }
}
