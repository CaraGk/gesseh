<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;
use Gesseh\UserBundle\Form\UserAdminType;

/**
 * StudentRegisterType
 */
class StudentRegisterType extends AbstractType
{
  private $testSimulActive;

  public function __construct($testSimulActive = true)
  {
    $this->testSimulActive = $testSimulActive;
  }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname')
            ->add('name')
            ->add('phone')
            ->add('address')
            ->add('user', new UserAdminType('Gesseh\UserBundle\Entity\User'), array(
                'label' => ' ',
            ))
            ->add('grade', 'entity', array(
                'class' => 'GessehUserBundle:Grade',
                'query_builder' => function (\Gesseh\UserBundle\Entity\GradeRepository $er) { return $er->getActiveQuery(); },
                'label' => 'Promotion',
            ))
        ;

        /* Si la simulation est activée */
        if ($this->testSimulActive == true) {
            $builder
                ->add('ranking')
                ->add('graduate')
            ;
        }
    }

  public function getName()
  {
    return 'gesseh_userbundle_studentregistertype';
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
