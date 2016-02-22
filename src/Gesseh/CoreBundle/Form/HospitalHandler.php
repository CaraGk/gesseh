<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\CoreBundle\Entity\Hospital;
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\CoreBundle\Entity\Repartition;

/**
 * HospitalType Handler
 */
class HospitalHandler
{
  private $form;
  private $request;
  private $em;
  private $periods;

  public function __construct(Form $form, Request $request, EntityManager $em, array $periods)
  {
    $this->form    = $form;
    $this->request = $request;
    $this->em      = $em;
    $this->periods = $periods;
  }

  public function process()
  {
    if ( $this->request->getMethod() == 'POST' ) {
      $this->form->bind($this->request);

      if ($this->form->isValid()) {
        $this->onSuccess(($this->form->getData()));

        return true;
      }
    }

    return false;
  }

  public function onSuccess(Hospital $hospital)
  {
    foreach($hospital->getDepartments() as $department) {
        if(!$department->getId()) {
            foreach($this->periods as $period) {
                $repartition = new Repartition();
                $repartition->setDepartment($department);
                $repartition->setPeriod($period);
                $repartition->setNumber(0);
                $this->em->persist($repartition);
            }
        }
    }
    $this->em->persist($hospital);
    $this->em->flush();
  }
}
