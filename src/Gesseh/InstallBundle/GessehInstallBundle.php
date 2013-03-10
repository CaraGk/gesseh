<?php

/**
 * This file is part of GESSEH project
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Marc Weistroff <marc.weistroff@sensio.com>
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: (c) Fabien Potencier <fabien@symfony.com>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\InstallBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Gesseh\InstallBundle\Configurator\Step\DoctrineStep;
use Gesseh\InstallBundle\Configurator\Step\MailerStep;
use Gesseh\InstallBundle\Configurator\Step\SecretStep;

/**
 * GessehInstallBundle.
 */
class GessehInstallBundle extends Bundle
{
    public function boot()
    {
        $configurator = $this->container->get('gesseh.install.webconfigurator');
        $configurator->addStep(new DoctrineStep($configurator->getParameters()));
        $configurator->addStep(new MailerStep($configurator->getParameters()));
        $configurator->addStep(new SecretStep($configurator->getParameters()));
    }
}
