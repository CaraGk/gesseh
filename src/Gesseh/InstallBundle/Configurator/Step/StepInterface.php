<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @author Marc Weistroff <marc.weistroff@sensio.com>
 * @copyright: (c) Fabien Potencier <fabien@symfony.com>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\InstallBundle\Configurator\Step;

use Symfony\Component\Form\Type\FormTypeInterface;

/**
 * StepInterface.
 */
interface StepInterface
{
    /**
     * __construct
     *
     * @param array $parameters
     */
    public function __construct(array $parameters);

    /**
     * Returns the form used for configuration.
     *
     * @return FormTypeInterface
     */
    public function getFormType();

    /**
     * Checks for requirements.
     *
     * @return array
     */
    public function checkRequirements();

    /**
     * Checks for optional setting it could be nice to have.
     *
     * @return array
     */
    public function checkOptionalSettings();

    /**
     * Returns the template to be renderer for this step.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Updates form data parameters.
     *
     * @param  array $parameters
     * @return array
     */
    public function update(StepInterface $data);
}
