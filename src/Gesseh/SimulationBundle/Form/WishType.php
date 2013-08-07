<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * WishType
 */
class WishType extends AbstractType
{
    private $username;

    public function __construct($rules)
    {
      $this->rules = $rules;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $rules = $this->rules;

      $builder
        ->add('department', 'entity', array(
          'class' => 'GessehCoreBundle:Department',
          'query_builder' => function(\Gesseh\CoreBundle\Entity\DepartmentRepository $er) use ($rules) { return $er->getAdaptedUserList($rules); },
        )
      );
    }

    public function getName()
    {
        return 'gesseh_simulationbundle_wishtype';
    }

  public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\SimulationBundle\Entity\Wish',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
