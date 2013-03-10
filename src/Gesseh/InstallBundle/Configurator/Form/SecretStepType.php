<?php

/**
 * This file is part of GESSEH project
 *
 * @author Marc Weistroff <marc.weistroff@sensio.com>
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: (c) Fabien Potencier <fabien@symfony.com>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\InstallBundle\Configurator\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Secret Form Type.
 */
class SecretStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('secret', 'text');
    }

    public function getName()
    {
        return 'installbundle_secret_step';
    }
}
