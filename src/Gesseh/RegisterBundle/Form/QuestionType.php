<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 * QuestionType
 */
class QuestionType extends AbstractType
{
    private $testSimulActive;

    public function __construct($questions)
    {
        $this->questions = $questions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach($this->questions as $question) {
            if($question->getType() == 1) {
                $builder->add('question_' . $question->getId(), 'choice', array(
                    'choices'  => $this->getQuestionSubjectiveChoiceOptions($question->getMore()),
                    'required' => true,
                    'multiple' => false,
                    'expanded' => true,
                    'label'    => $question->getName(),
                 ));
            } elseif($question->getType() == 2) {
                $builder->add('question_' . $question->getId(), 'textarea', array(
                    'required'   => true,
                    'trim'       => true,
                    'max_length' => 250,
                    'label'      => $question->getName(),
                ));
            } elseif ($question->getType() == 3) {
                $builder->add('question_' . $question->getId(), 'choice', array(
                    'choices'  => $this->getQuestionChoiceOptions($question->getMore()),
                    'required' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'label'    => $question->getName(),
                ));
            } elseif ($question->getType() == 4) {
                $builder->add('question_' . $question->getId(), 'integer', array(
                    'precision' => $question->getMore(),
                    'required'  => true,
                    'label'     => $question->getName(),
                ));
            } elseif ($question->getType() == 5) {
                $builder->add('question_' . $question->getId(), 'choice', array(
                    'choices'  => $this->getQuestionChoiceOptions($question->getMore(), array(0)),
                    'required' => true,
                    'multiple' => false,
                    'expanded' => true,
                    'label'    => $question->getName(),
                ));
            } elseif ($question->getType() == 6) {
                $builder->add('question_' . $question->getId(), 'time', array(
                    'input'        => 'string',
                    'widget'       => 'single_text',
                    'with_seconds' => false,
                    'required'     => true,
                    'label'        => $question->getName(),
                ));
            }
        }
        $builder->add('Enregistrer', 'submit');
    }

    public function getName()
    {
        return 'gesseh_registerbundle_questiontype';
    }

    public function getQuestionSubjectiveChoiceOptions($options)
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

    public function getQuestionChoiceOptions($options, $except = array())
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
