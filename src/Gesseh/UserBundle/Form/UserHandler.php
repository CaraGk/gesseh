<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager,
    Gesseh\UserBundle\Entity\User;

/**
 * UserType Handler
 */
class UserHandler
{
  private $form;
  private $request;
  private $um;

  public function __construct(Form $form, Request $request, UserManager $um)
  {
    $this->form    = $form;
    $this->request = $request;
    $this->um      = $um;
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

  public function onSuccess(User $user)
  {
    $user->setConfirmationToken(null);
    $user->setEnabled(true);
    $user->addRole('ROLE_ADMIN');
    $user->setUsername($user->getEmail());

    $this->um->updateUser($user);
  }
}

