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
    $builder->add('surname')
            ->add('name')
            ->add('phone')
            ->add('user', new UserType('Gesseh\UserBundle\Entity\User'))
            ->add('grade');

    // Si la simulation est activée
    //if ($this->getContainer()->get('kbd_parameters.manager')->findParamByName('simulation_active')->getValue()) {
    if ($this->testSimulActive == true) {
      $builder->add('ranking')
              ->add('graduate');
    }
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
