<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
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

  public function __construct(Form $form, Request $request, EntityManager $em, \Gesseh\CoreBundle\Entity\Placement $placement, $criterias, $moderate)
  {
    $this->form      = $form;
    $this->request   = $request;
    $this->em        = $em;
    $this->placement = $placement;
    $this->criterias = $criterias;
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
            foreach ($this->criterias as $criteria_item) {
                if ($criteria_item->getId() == $criteria_id[1]) {
                    $criteria_orig = $criteria_item;
                    break;
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
        $eval_criteria = new Evaluation();


        if ($criteria->getRequired() == false and $value == null)
            continue;

        if(($this->moderate == true and $criteria->getModerate() == false) or $this->moderate == false)
            $eval_criteria->setModerated(true);
        else
            $eval_criteria->setModerated(false);

        $eval_criteria->setPlacement($this->placement);
        $eval_criteria->setEvalCriteria($criteria);
        $eval_criteria->setCreatedAt(new \DateTime('now'));
        $eval_criteria->setValue($value);

        $this->em->persist($eval_criteria);
    }
}
