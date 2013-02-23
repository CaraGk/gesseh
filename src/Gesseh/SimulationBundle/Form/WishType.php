<?php

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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

    public function getDefaultOptions(array $options)
    {
      return array(
        'data_class' => 'Gesseh\SimulationBundle\Entity\Wish',
      );
    }
}
