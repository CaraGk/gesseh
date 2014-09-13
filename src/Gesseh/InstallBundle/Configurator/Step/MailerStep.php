<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\InstallBundle\Configurator\Step;

use Gesseh\InstallBundle\Configurator\Form\MailerStepType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mailer Step.
 */
class MailerStep implements StepInterface
{
  /**
   * @Assert\Choice(callback="getTransportKeys")
   */
  public $transport;

  /**
   * @Assert\NotBlank
   */
  public $host;

  public $user;

  public $password;

  /**
   * @Assert\NotBlank
   */
  public $mail;

  public function __construct(array $parameters)
  {
    foreach ($parameters as $key => $value) {
      if (0 === strpos($key, 'mailer_')) {
        $parameters[substr($key, 7)] = $value;
        $key = substr($key, 7);
        $this->$key = $value;
      }
    }
  }

  /**
   * @see StepInterface
   */
  public function getFormType()
  {
    return new MailerStepType();
  }

  /**
   * @see StepInterface
   */
  public function checkRequirements()
  {
    return array();
  }

  /**
   * @see StepInterface
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
    $parameters = array();

    foreach ($data as $key => $value) {
      $parameters['mailer_'.$key] = $value;
    }

    return $parameters;
  }

  /**
   * @see StepInterface
   */
  public function getTemplate()
  {
    return 'GessehInstallBundle:Configurator:Step/mailer.html.twig';
  }

  /**
   * @return array
   */
  public static function getTransportKeys()
  {
    return array_keys(static::getTransports());
  }

  /**
   * @return array
   */
  public static function getTransports()
  {
    return array(
      'smtp' => 'SMTP',
    );
  }
}
