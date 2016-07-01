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

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\EvaluationBundle\Entity\Evaluation;

/**
 * EvaluationType Handler
 */
class EvaluationHandler
{
  private $form;
  private $request;
  private $em;
  private $placement;
  private $eval_forms;
  private $moderate;

  public function __construct(Form $form, Request $request, EntityManager $em, \Gesseh\CoreBundle\Entity\Placement $placement, array $eval_forms, $moderate)
  {
    $this->form      = $form;
    $this->request   = $request;
    $this->em        = $em;
    $this->placement = $placement;
    $this->eval_forms = $eval_forms;
    $this->moderate  = $moderate;
  }

  public function process()
  {
    if ($this->request->getMethod() == 'POST') {
      $this->form->bind($this->request);

      if ($this->form->isValid()) {
        $this->onSuccess(($this->form->getData()));

        return true;
      }
    }

    return false;
  }

    public function onSuccess($form)
    {
        foreach ($form as $criteria => $value) {

            /* Identification du critère d'évaluation correspondant */
            $criteria_id = explode("_", $criteria);
            $criteria_orig = null;
            foreach ($this->eval_forms as $eval_form) {
                foreach ($eval_form->getCriterias() as $criteria_item) {
                    if ($criteria_item->getId() == $criteria_id[1]) {
                        $criteria_orig = $criteria_item;
                        break;
                    }
                }
            }
            if($criteria_orig->getType() == 3) {
                foreach($value as $item) {
                    $this->setEvalItem($criteria_orig, $item);
                }
            } else {
                $this->setEvalItem($criteria_orig, $value);
            }
        }
        $this->em->flush();
    }

    private function setEvalItem($criteria, $value)
    {
        if ($criteria->isRequired() == true or $value != null) {
            $eval_criteria = new Evaluation();

            if(($this->moderate == true and $criteria->isModerate() == false) or $this->moderate == false)
                $eval_criteria->setValidated(true);
            else
                $eval_criteria->setValidated(false);

            $eval_criteria->setPlacement($this->placement);
            $eval_criteria->setEvalCriteria($criteria);
            $eval_criteria->setCreatedAt(new \DateTime('now'));
            $eval_criteria->setValue($value);

            $this->em->persist($eval_criteria);
        }
    }
}
