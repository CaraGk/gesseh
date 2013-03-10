<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\InstallBundle\Configurator\Form;

use Gesseh\InstallBundle\Configurator\Step\MailerStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Mailer Form Type.
 */
class MailerStepType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('transport', 'choice', array('choices' => MailerStep::getTransports()))
      ->add('host', 'text')
      ->add('user', 'text')
      ->add('password', 'repeated', array(
        'required' => false,
        'type'     => 'password',
        'first_name' => 'password',
        'second_name' => 'password_again',
        'invalid_message' => 'Les champs mot de passe doivent correspondre.',
      ))
      ->add('mail', 'text')
    ;
  }

  public function getName()
  {
    return 'installbundle_mailer_step';
  }
}
