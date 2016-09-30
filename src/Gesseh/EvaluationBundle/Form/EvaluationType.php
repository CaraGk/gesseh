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
                    $label = $criteria->getName();
                if ($criteria->isPrivate() == true) {
                    $tooltip = array(
                        'title' => 'Consultation restreinte aux étudiants',
                        'text'  => 'accès restreint',
                        'icon'  => 'eye-close',
                    );
                } else {
                    $tooltip = array(
                        'title' => 'Consultation ouverte aux enseignants après pondération sur plusieurs évaluations pour garantir l\'anonymat',
                        'text'  => 'visible',
                        'icon'  => 'eye-open',
                    );
                }

                if ($criteria->getType() == 1) {
                    $builder->add('criteria_' . $criteria->getId(), 'choice', array(
                        'choices'    => $this->getCriteriaSubjectiveChoiceOptions($criteria->getMore()),
                        'required'   => $criteria->isRequired(),
                        'multiple'   => false,
                        'expanded'   => true,
                        'label'      => $label,
                        'help_label_tooltip' => $tooltip,
                    ));
                } elseif ($criteria->getType() == 2) {
                    $builder->add('criteria_' . $criteria->getId(), 'textarea', array(
                        'required'   => $criteria->isRequired(),
                        'trim'       => true,
                        'label'      => $label,
                        'help_label_tooltip' => $tooltip,
                    ));
                } elseif ($criteria->getType() == 3) {
                    $builder->add('criteria_' . $criteria->getId(), 'choice', array(
                        'choices'    => $this->getCriteriaChoiceOptions($criteria->getMore()),
                        'required'   => $criteria->isRequired(),
                        'multiple'   => true,
                        'expanded'   => true,
                        'label'      => $label,
                        'help_label_tooltip' => $tooltip,
                    ));
                } elseif ($criteria->getType() == 4) {
                    $builder->add('criteria_' . $criteria->getId(), 'integer', array(
                        'precision'  => $criteria->getMore(),
                        'required'   => $criteria->isRequired(),
                        'label'      => $label,
                        'horizontal_input_wrapper_class' => 'col-lg-4',
                        'help_label_tooltip' => $tooltip,
                    ));
                } elseif ($criteria->getType() == 5) {
                    $builder->add('criteria_' . $criteria->getId(), 'choice', array(
                        'choices'    => $this->getCriteriaChoiceOptions($criteria->getMore(), array(0)),
                        'required'   => $criteria->isRequired(),
                        'multiple'   => false,
                        'expanded'   => true,
                        'label'      => $label,
                        'help_label_tooltip' => $tooltip,
                    ));
                } elseif ($criteria->getType() == 6) {
                    $builder->add('criteria_' . $criteria->getId(), 'time', array(
                        'input'        => 'string',
                        'widget'       => 'single_text',
                        'with_seconds' => false,
                        'required'     => $criteria->isRequired(),
                        'label'        => $label,
                        'help_label_tooltip' => $tooltip,
                        'horizontal_input_wrapper_class' => 'col-lg-4',
                        'timepicker'   => true,
                    ));
                } elseif ($criteria->getType() == 7) {
                    $options = explode('|', $criteria->getMore());
                    $legend = '<span class="col-sm-6">' . $options[1] . '</span><span class="col-sm-6 right">' . $options[2] . '</span>';
                    $builder->add('criteria_' . $criteria->getId(), 'range', array(
                        'required'   => $criteria->isRequired(),
                        'label'      => $label,
                        'help_block' => $legend,
                        'help_label_tooltip' => $tooltip,
                        'attr'       => array('min' => 0, 'max' => 100,),
                        'data'       => $options[0],
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
