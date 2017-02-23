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
    Symfony\Component\Form\Extension\Core\Type\TextType,
    Symfony\Component\Form\Extension\Core\Type\ChoiceType,
    Symfony\Component\Form\Extension\Core\Type\CollectionType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gesseh\UserBundle\Form\UserType,
    Gesseh\RegisterBundle\Form\FilterType;

/**
 * PartnerType
 */
class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questions = $options['questions'];

        $builder
            ->add('name', TextType::class, array(
                'label'   => 'Nom',
            ))
            ->add('user', UserType::class, array(
                'label'   => ' ',
            ))
            ->add('filters', CollectionType::class, array(
                'label'        => 'Filtres',
                'entry_type'   => FilterType::class,
                'entry_options' => array(
                    'questions' => $questions,
                ),
                'allow_add'    => true,
                'allow_delete' => true,
                'delete_empty' => true,
            ))
            ->add('limits', ChoiceType::class, array(
                'label' => 'Restrictions d\'accès',
                'choices' => array(
                    'Téléphone' => 'telephone',
                    'Adresse'   => 'address',
                ),
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function getName()
    {
        return 'gesseh_registerbundle_partnertype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\RegisterBundle\Entity\Partner',
            'questions'  => null,
        ));
    }
}

