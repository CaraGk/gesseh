<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GessehCoreBundle extends Bundle
{
  public function boot()
  {
    $em = $this->container->get('doctrine.orm.default_entity_manager');

    $em->getConnection()->getDatabasePlatform();
  }
}
