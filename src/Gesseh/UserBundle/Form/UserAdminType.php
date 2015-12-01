<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

/**
 * UserAdminType
 */
class UserAdminType extends BaseType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('email', 'email')
            ->add('plainPassword', 'repeated', array(
              'first_name' => 'password',
              'second_name' => 'confirm',
              'type' => 'password',
            ));
  }

  public function getName()
  {
    return 'gesseh_user_admin';
  }

  public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\UserBundle\Entity\User',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
