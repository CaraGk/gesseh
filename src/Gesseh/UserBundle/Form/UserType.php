<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

/**
 * UserType
 */
class UserType extends BaseType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('email', 'email');
  }

  public function getName()
  {
    return 'gesseh_user';
  }
}
