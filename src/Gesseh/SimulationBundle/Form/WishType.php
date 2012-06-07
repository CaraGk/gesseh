<?php

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class WishType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('department')
        ;
    }

    public function getName()
    {
        return 'gesseh_simulationbundle_wishtype';
    }

    public function getDefaultOptions(array $options)
    {
      return array(
        'data_class' => 'Gesseh\SimulationBundle\Entity\Wish'
      );
    }
}
