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
 * StudentType
 */
class StudentType extends AbstractType
{
  private $testSimulActive;

  public function __construct($testSimulActive)
  {
    $this->testSimulActive = $testSimulActive;
  }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname')
            ->add('name')
            ->add('phone')
            ->add('user', new UserType('Gesseh\UserBundle\Entity\User'), array(
                'label' => ' ',
            ))
            ->add('grade')
        ;

        /* Si la simulation est activée */
        if ($this->testSimulActive == true) {
            $builder
                ->add('ranking')
                ->add('graduate')
            ;
        }

        $builder
            ->add('Ajouter', 'submit')
        ;
    }

  public function getName()
  {
    return 'gesseh_userbundle_studenttype';
  }

  public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\UserBundle\Entity\Student',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
