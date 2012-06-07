<?php
// src/Gesseh/SimulationBundle/Form/WishHandler.php

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\SimulationBundle\Entity\Wish;

class WishHandler
{
  private $form;
  private $request;
  private $em;

  public function __construct(Form $form, Request $request, EntityManager $em, \Gesseh\UserBundle\Entity\Student $student)
  {
    $this->form    = $form;
    $this->request = $request;
    $this->em      = $em;
    $this->student = $student;
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
    $wish->setStudent($this->student);
    $wish->setRank("1");
    $this->em->persist($wish);
    $this->em->flush();
  }
}
