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

use Symfony\Component\Form\AbstractType,
  Symfony\Component\Form\FormBuilderInterface;

/**
 * AdminType
 */
class AdminType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('surname')
            ->add('name')
            ->add('phone')
            ->add('user', new UserAdminType('Gesseh\UserBundle\Entity\User'))
            ->add('grade');
  }

  public function getName()
  {
    return 'gesseh_userbundle_admintype';
 {
  public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\UserBundle\Entity\Student',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
