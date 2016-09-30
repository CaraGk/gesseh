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
    Symfony\Component\Form\Extension\Core\Type\TextType,
    Symfony\Component\Form\Extension\Core\Type\ChoiceType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Gesseh\UserBundle\Form\AddressType;

/**
 * GatewayConfigType
 */
class GatewayConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
            'required' => false,
        ))
        ->add('password', TextType::class, array(
            'required' => false,
        ))
        ->add('signature', TextType::class, array(
            'required' => false,
        ))
        ->add('payableTo', TextType::class, array(
            'label' => 'Ordre (chèque)',
            'required' => false,
        ))
        ->add('address', AddressType::class, array(
            'label' => 'Adresse d\'envoi (chèque)',
            'required' => false,
        ))
        ;
    }

    public function getName()
    {
        return 'gesseh_registerbundle_gatewayconfigtype';
    }
}

