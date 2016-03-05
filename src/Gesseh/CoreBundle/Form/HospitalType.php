<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Gesseh\CoreBundle\Form\DepartmentType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType,
    Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * HospitalType
 */
class HospitalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('web')
            ->add('phone')
            ->add('departments', CollectionType::class, array(
                'entry_type' => DepartmentType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'show_legend' => false,
                'entry_options' => array(
                    'label_render' => false,
                    'widget_addon_prepend' => array(
                        'text' => '@',
                    ),
                    'horizontal_input_wrapper_class' => "col-lg-8",
            )))
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\CoreBundle\Entity\Hospital',
        ));

        $resolver->setAllowedValues(array(
        ));
    }
}
