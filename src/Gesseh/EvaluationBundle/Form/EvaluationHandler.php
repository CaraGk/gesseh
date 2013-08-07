<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
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

  public function __construct(Form $form, Request $request, EntityManager $em, \Gesseh\CoreBundle\Entity\Placement $placement, $criterias)
  {
    $this->form      = $form;
    $this->request   = $request;
    $this->em        = $em;
    $this->placement = $placement;
    $this->criterias = $criterias;
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
      $eval_criteria = new Evaluation();

      $criteria_id = explode("_", $criteria);
      foreach ($this->criterias as $criteria_orig) {
        if ($criteria_orig->getId() == $criteria_id[1]) {
          $eval_criteria->setEvalCriteria($criteria_orig);
          break;
        }
      }

      if ($eval_criteria->getEvalCriteria()->getType() == 2 and $value == null)
        continue;

      $eval_criteria->setPlacement($this->placement);
      $eval_criteria->setEvalCriteria($criteria_orig);
      $eval_criteria->setValue($value);
      $eval_criteria->setCreatedAt(new \DateTime('now'));

      $this->em->persist($eval_criteria);
    }
    $this->em->flush();
  }
}
