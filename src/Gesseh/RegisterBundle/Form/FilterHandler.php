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

use Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Request;

/**
 * FilterType Handler
 */
class FilterHandler
{
    private $form, $request, $questions;

    public function __construct(Form $form, Request $request, $questions)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->questions = $questions;
    }

    public function process()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $filter = $this->onSuccess($this->form->getData());

                return $filter;
            }
        }

        return false;
    }

    public function onSuccess($data)
    {
        $question_id = $data['question'];
        $value = $data['value_' . $question_id];
        foreach ($this->questions as $question) {
            if ($question->getId() == $question_id)
                $label = $question->getName() . " : " . $value;
        }

        return array(
            'label'    => $label,
            'question' => $question_id,
            'value'    => $value,
        );
    }
}
