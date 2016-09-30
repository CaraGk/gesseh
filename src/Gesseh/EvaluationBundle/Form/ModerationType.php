<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolver;
use Gesseh\EvaluationBundle\Entity\Evaluation;

/**
 * Moderation type
 */
class ModerationType extends AbstractType
{
    private $evaluation;

    public function __construct(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $criteria = $this->evaluation->getEvalCriteria();
        if ($criteria->getType() == 1) {
            $builder->add('value', 'choice', array(
                'choices'    => $this->getCriteriaSubjectiveChoiceOptions($criteria->getMore()),
                'required'   => $criteria->isRequired(),
                'multiple'   => false,
                'expanded'   => true,
                'label'      => $criteria->getName(),
                'data'       => $this->evaluation->getValue(),
            ));
        } elseif ($criteria->getType() == 2) {
            $builder->add('value', 'textarea', array(
                'required'   => $criteria->isRequired(),
                'trim'       => true,
                'label'      => $criteria->getName(),
                'data'       => $this->evaluation->getValue(),
            ));
        } elseif ($criteria->getType() == 3) {
            $builder->add('value', 'choice', array(
                'choices'    => $this->getCriteriaChoiceOptions($criteria->getMore()),
                'required'   => $criteria->isRequired(),
                'multiple'   => true,
                'expanded'   => true,
                'label'      => $criteria->getName(),
                'data'       => $this->evaluation->getValue(),
            ));
        } elseif ($criteria->getType() == 4) {
            $builder->add('value', 'integer', array(
                'precision'  => $criteria->getMore(),
                'required'   => $criteria->isRequired(),
                'label'      => $criteria->getName(),
                'data'       => $this->evaluation->getValue(),
            ));
        } elseif ($criteria->getType() == 5) {
            $builder->add('value', 'choice', array(
                'choices'    => $this->getCriteriaChoiceOptions($criteria->getMore(), array(0)),
                'required'   => $criteria->isRequired(),
                'multiple'   => false,
                'expanded'   => true,
                'label'      => $criteria->getName(),
                'data'       => $this->evaluation->getValue(),
            ));
        } elseif ($criteria->getType() == 6) {
            $builder->add('value', 'time', array(
                'input'        => 'string',
                'widget'       => 'single_text',
                'with_seconds' => false,
                'required'     => $criteria->isRequired(),
                'label'        => $criteria->getName(),
                'data'         => $this->evaluation->getValue(),
            ));
        }
        $builder->add('Enregister', 'submit');
    }

    public function getName()
    {
        return 'gesseh_evaluationbundle_moderationtype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => false,
        ));
    }

    private function getCriteriaSubjectiveChoiceOptions($options)
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

