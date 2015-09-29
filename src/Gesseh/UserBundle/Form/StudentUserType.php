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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * StudentUserType
 */
class StudentUserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
            ->add('title', 'choice', array(
                'label' => 'Titre',
                'choices' => array(
                    'M.' => 'M.',
                    'Mme' => 'Mme',
                    'Mlle' => 'Mlle',
                ),
            ))
            ->add('surname', 'text', array(
                'label' => 'Nom',
            ))
            ->add('name', 'text', array(
                'label' => 'Prénom',
            ))
            ->add('birthday', 'birthday', array(
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('birthplace', 'text', array(
                'label' => 'Lieu de naissance',
            ))
            ->add('anonymous')
            ->add('user', new UserType('Gesseh\UserBundle\Entity\User'), array(
                'label' => ' ',
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone',
            ))
            ->add('address', new AddressType(), array(
                'label' => 'Adresse :'
            ))
    ;
  }

  public function getName()
  {
    return 'gesseh_userbundle_studenttype';
  }

  public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\UserBundle\Entity\Student',
        'cascade_validation' => true,
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
