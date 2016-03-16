<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * SimulationType
 */
class SimulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('department', null, array(
                    'required' => false,
                ))
                ->add('is_excess', null, array(
                    'required' => false,
                    'label' => 'Surnombre',
                ))
                ->add('active', null, array(
                    'required' => false,
                    'label'    => 'Actif',
                ))
                ->add('Valider', 'button')
        ;
    }

    public function getName()
    {
        return 'gesseh_simulationbundle_simulationtype';
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\SimulationBundle\Entity\Simulation',
        ));

        $resolver->setAllowedValues(array(
        ));
    }
}
