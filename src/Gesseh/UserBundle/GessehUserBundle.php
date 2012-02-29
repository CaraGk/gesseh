<?php

namespace Gesseh\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GessehUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
