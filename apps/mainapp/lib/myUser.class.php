<?php

class myUser extends sfGuardSecurityUser
{
  public function getEtudiantId()
  {
    $users = $this->getGuardUser()->getGessehEtudiant();
    foreach ($users as $user)
      $userid = $user->getId();

    return $userid;
  }
}
