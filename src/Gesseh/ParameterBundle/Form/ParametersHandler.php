<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\ParameterBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use KDB\ParametersBundle\Entity\ParameterManager;
use Gesseh\ParameterBundle\Entity\Parameter;

/**
 * ParametersType Handler
 */
class ParametersHandler
{
  private $form;
  private $request;
  private $em;

  public function __construct(Form $form, Request $request, ParameterManager $pm, array $parameters)
  {
    $this->form       = $form;
    $this->request    = $request;
    $this->pm         = $pm;
    $this->parameters = $parameters;
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

  public function onSuccess($data)
  {
      foreach ($this->parameters as $parameter) {
        if($data[$parameter->getName()] == null)
          $data[$parameter->getName()] = 0;
        $parameter->setValue($data[$parameter->getName()]);
        $this->pm->persistParameter($parameter);
    }
  }
}
