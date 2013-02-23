<?php

namespace Gesseh\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\DBAL\Types\Type;

class GessehCoreBundle extends Bundle
{
  public function boot() {
    $em = $this->container->get('doctrine.orm.default_entity_manager');

    $em->getConnection()->getDatabasePlatform();
  }
}
