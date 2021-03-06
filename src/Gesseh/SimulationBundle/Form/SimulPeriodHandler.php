<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\SimulationBundle\Entity\SimulPeriod;

/**
 * SimulPeriodType Handler
 */
class SimulPeriodHandler
{
  private $form;
  private $request;
  private $em;
  private $period;

  public function __construct(Form $form, Request $request, EntityManager $em, \Gesseh\CoreBundle\Entity\Period $period)
  {
    $this->form    = $form;
    $this->request = $request;
    $this->em      = $em;
    $this->period  = $period;
  }

  public function process()
  {
    if ($this->request->getMethod() == 'POST') {
      $this->form->bind($this->request);

      if ($this->form->isValid()) {
        $this->onSuccess($this->form->getData());

        return true;
      }
    }

    return false;
  }

  public function onSuccess(SimulPeriod $simul_period)
  {
      if (!$simul_period->getPeriod()) {
          $simul_period->setPeriod($period);
      }
    $this->em->persist($simul_period);
    $this->em->flush();
  }
}
