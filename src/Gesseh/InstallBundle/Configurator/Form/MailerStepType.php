<?php

namespace Gesseh\InstallBundle\Configurator\Form;

use Gesseh\InstallBundle\Configurator\Step\MailerStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Mailer Form Type.
 *
 * @author PF Angrand <pilou@angrand.fr>
 */
class MailerStepType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
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
