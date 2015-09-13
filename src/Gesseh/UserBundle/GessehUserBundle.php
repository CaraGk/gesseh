<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

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
