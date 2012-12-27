<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gesseh\InstallBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Gesseh\InstallBundle\Configurator\Step\DoctrineStep;
use Gesseh\InstallBundle\Configurator\Step\SecretStep;

/**
 * GessehInstallBundle.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Marc Weistroff <marc.weistroff@sensio.com>
 */
class GessehInstallBundle extends Bundle
{
    public function boot()
    {
        $configurator = $this->container->get('gesseh.install.webconfigurator');
        $configurator->addStep(new DoctrineStep($configurator->getParameters()));
        $configurator->addStep(new SecretStep($configurator->getParameters()));
    }
}
