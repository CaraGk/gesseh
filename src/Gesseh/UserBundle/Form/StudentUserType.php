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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * StudentUserType
 */
class StudentUserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('surname')
            ->add('name')
            ->add('anonymous')
            ->add('phone')
            ->add('user', new UserType('Gesseh\UserBundle\Entity\User'));
  }

  public function getName()
  {
    return 'gesseh_userbundle_studenttype';
  }

  public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\UserBundle\Entity\Student',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
