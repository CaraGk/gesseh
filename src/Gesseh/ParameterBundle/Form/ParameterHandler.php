<?php
// src/Gesseh/ParameterBundle/Form/ParametersHandler.php

namespace Gesseh\ParameterBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\ParameterBundle\Entity\Parameter;

class ParameterHandler
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
    if( $this->request->getMethod() == 'POST' ) {
      $this->form->bindRequest($this->request);

      if ($this->form->isValid()) {
        $this->onSuccess(($this->form->getData()));

        return true;
      }
    }

    return false;
  }

  public function onSuccess(Parameter $parameter)
  {
    $this->em->persist($parameter);
    $this->em->flush();
  }
}
