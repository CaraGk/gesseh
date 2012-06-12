<?php

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class WishType extends AbstractType
{
    private $username;

    public function __construct($user)
    {
      $this->username = $user;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
      $username = $this->username;

      $builder
        ->add('department', 'entity', array(
          'class' => 'GessehCoreBundle:Department',
          'query_builder' => function(\Gesseh\CoreBundle\Entity\DepartmentRepository $er) use ($username) { return $er->getAdaptedUserList($username); },
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
