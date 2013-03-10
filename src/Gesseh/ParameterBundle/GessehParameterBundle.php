<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\ParameterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GessehParameterBundle extends Bundle
{
  public function getParent()
  {
    return 'KDBParametersBundle';
  }
}
