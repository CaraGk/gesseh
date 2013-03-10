<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\SimulationBundle\Entity\Wish;

/**
 * WishType Handler
 */
class WishHandler
{
  private $form;
  private $request;
  private $em;

  public function __construct(Form $form, Request $request, EntityManager $em, \Gesseh\SimulationBundle\Entity\Simulation $simstudent)
  {
    $this->form    = $form;
    $this->request = $request;
    $this->em      = $em;
    $this->simstudent = $simstudent;
  }

  public function process()
  {
    if( $this->request->getMethod() == 'POST' ) {
      $this->form->bindRequest($this->request);

      if ($this->form->isValid()) {
        $this->onSuccess(($this->form->getData()));

        return true;
      }
    }
    return false;
  }

  public function onSuccess(Wish $wish)
  {
    $wish->setSimstudent($this->simstudent);
    $wish->setRank($this->em->getRepository('GessehSimulationBundle:Wish')->getMaxRank($this->simstudent->getStudent())+1);
    $this->em->persist($wish);
    $this->em->flush();
  }
}
