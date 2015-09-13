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
    Symfony\Component\HttpFoundation\Request,
    Doctrine\ORM\EntityManager,
    Gesseh\RegisterBundle\Entity\MemberInfo;

/**
 * QuestionType Handler
 */
class QuestionHandler
{
    private $form, $request, $em, $membership, $questions;

    public function __construct(Form $form, Request $request, EntityManager $em, \Gesseh\RegisterBundle\Entity\Membership $membership, $questions)
    {
      $this->form       = $form;
      $this->request    = $request;
      $this->em         = $em;
      $this->membership = $membership;
      $this->questions  = $questions;
    }

    public function process()
    {
        if($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            if($this->form->isValid()) {
                $this->onSuccess($this->form->getData());

                return true;
            }
        }

        return false;
    }

    public function onSuccess($form)
    {
        foreach($form as $question => $value) {
            $question_id = explode("_", $question);
            $question_orig = null;
            foreach($this->questions as $question_item) {
                if($question_item->getId() == $question_id[1]) {
                    $question_orig = $question_item;
                    break;
                }
            }
            if($question_orig->getType() == 3) {
                foreach($value as $item) {
                    $this->setQuestionInfo($question_orig, $item);
                }
            } else {
                $this->setQuestionInfo($question_orig, $value);
            }
        }
        $this->em->flush();
    }

    private function setQuestionInfo($question, $value)
    {
        $member_info = new MemberInfo();
        $member_info->setMembership($this->membership);
        $member_info->setValue($value);
        $member_info->setQuestion($question);

        $this->em->persist($member_info);
    }
}
