<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\UserBundle\Entity\Grade;

/**
 * GradeType Handler
 */
class GradeHandler
{
  private $form;
  private $request;
  private $em;

  public function __construct(Form $form, Request $request, EntityManager $em)
  {
    $this->form    = $form;
    $this->request = $request;
    $this->em      = $em;
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

  public function onSuccess(Grade $grade)
  {
    $rank = $this->em->getRepository('GessehUserBundle:Grade')->getLastActiveRank();
    if( $grade->getRank() > $rank + 1 )
      $grade->setRank($rank + 1);
    $this->em->getRepository('GessehUserBundle:Grade')->updateNextRank($grade->getRank());
    $this->em->persist($grade);
    $this->em->flush();
  }
}
