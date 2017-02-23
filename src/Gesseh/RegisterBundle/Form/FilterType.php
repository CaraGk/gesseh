<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\Extension\Core\Type\ChoiceType,
    Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * FilterType
 */
class FilterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questions = $options['questions'];
        $columns = array();
        foreach ($questions as $question) {
            $columns[$question->getId()] = $question->getName();
        }

        $builder
            ->add('column', ChoiceType::class, array(
                'label'    => 'Critère de filtre',
                'multiple' => false,
                'expanded' => false,
                'choices'  => $columns,
            ))
            ->add('value', TextType::class, array(
                'label' => 'Valeur',
            ))
        ;
    }

    public function getName()
    {
        return 'gesseh_registerbundle_filtertype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'questions' => null,
        ));
    }
}
