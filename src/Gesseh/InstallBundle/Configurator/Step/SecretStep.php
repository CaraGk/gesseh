<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @author Fabien Potencier <fabien@symfony.com>
 * @copyright: (c) Fabien Potencier <fabien@symfony.com>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\InstallBundle\Configurator\Step;

use Gesseh\InstallBundle\Configurator\Form\SecretStepType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Secret Step.
 */
class SecretStep implements StepInterface
{
    /**
     * @Assert\NotBlank
     */
    public $secret;

    public function __construct(array $parameters)
    {
        $this->secret = $parameters['secret'];

        if ('ThisTokenIsNotSoSecretChangeIt' == $this->secret) {
            $this->secret = hash('sha1', uniqid(mt_rand()));
        }
    }

    /**
     * @see StepInterface
     */
    public function getFormType()
    {
        return new SecretStepType();
    }

    /**
     * @see StepInterface
     */
    public function checkRequirements()
    {
        return array();
    }

    /**
     * checkOptionalSettings
     */
    public function checkOptionalSettings()
    {
        return array();
    }

    /**
     * @see StepInterface
     */
    public function update(StepInterface $data)
    {
        return array('secret' => $data->secret);
    }

    /**
     * @see StepInterface
     */
    public function getTemplate()
    {
        return 'GessehInstallBundle:Configurator/Step:secret.html.twig';
    }
}
