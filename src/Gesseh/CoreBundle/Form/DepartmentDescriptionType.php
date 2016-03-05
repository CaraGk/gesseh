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
use Symfony\Component\Form\Extension\Core\Type\SubmitType,
    Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * DepartmentDescriptionType
 */
class DepartmentDescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description', TextareaType::class, array(
            'attr' => array(
                'class'      => 'tinymce',
                'data-theme' => 'medium'
            ),
        ))
                ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\CoreBundle\Entity\Department',
        ));

        $resolver->setAllowedValues(array(
        ));
    }
}
