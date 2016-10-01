<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\Extension\Core\Type\ChoiceType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * GatewayType
 */
class GatewayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('factoryName', ChoiceType::class, array(
                'label'   => 'Type',
                'choices' => array(
                    'Chèques, virement ou espèces' => 'offline',
                    'Paypal'                       => 'paypal_express_checkout',
                ),
                'multiple' => false,
                'expanded' => false,
                'choices_as_values' => true,
            ))
            ->add('config', GatewayConfigType::class, array(
                'label'   => 'Configuration',
                'required' => false,
            ))
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function getName()
    {
        return 'gesseh_registerbundle_gatewaytype';
    }
}

