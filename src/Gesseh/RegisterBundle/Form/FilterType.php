<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 * FilterType
 */
class FilterType extends AbstractType
{
    private $questions;

    public function __construct($questions)
    {
        $this->questions = $questions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach($this->questions as $question) {
            $choices[$question->getId()] = $question->getName();
        }

        $builder->add('question', 'choice', array(
            'choices'  => $choices,
            'required' => true,
            'multiple' => false,
            'expanded' => false,
            'label'    => '',
        ));

        foreach($this->questions as $question) {
            $builder->add('value_' . $question->getId(), 'choice', array(
                'choices'  => $question->getMore(),
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'label'    => '',
            ));
        }

        $builder->add('Filtrer', 'submit');
    }

    public function getName()
    {
        return 'gesseh_registerbundle_filtertype';
    }
}
