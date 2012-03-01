<?php

namespace Gesseh\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GessehUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }

  public function boot()
  {
    $em = $this->container->get('doctrine.orm.default_entity_manager');

    $em->getConnection()->getDatabasePlatform();
  }
}
