<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 * ImportType
 */
class ImportType extends AbstractType
{
    private $choices;

    public function __construct()
    {
        $this->choices = array(
            '0'  => '1re colonne (A)',
            '1'  => '2e colonne (B)',
            '2'  => '3e colonne (C)',
            '3'  => '4e colonne (D)',
            '4'  => '5e colonne (E)',
            '5'  => '6e colonne (F)',
            '6'  => '7e colonne (G)',
            '7'  => '8e colonne (H)',
            '8'  => '9e colonne (I)',
            '9'  => '10e colonne (J)',
            '10' => '11e colonne (K)',
            '11' => '12e colonne (L)',
            '12' => '13e colonne (M)',
            '13' => '14e colonne (N)',
            '14' => '15e colonne (O)',
            '15' => '16e colonne (P)',
            '16' => '17e colonne (Q)',
            '17' => '18e colonne (R)',
            '18' => '19e colonne (S)',
            '19' => '20e colonne (T)',
            '20' => '21e colonne (U)',
            '21' => '22e colonne (V)',
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label'    => 'Fichier',
                'required' => true,
            ))
            ->add('first_row', 'checkbox', array(
                'label'    => 'Le fichier contient une ligne de titre de colonnes',
                'required' => false,
                'data'     => true,
            ))
            ->add('title', 'choice', array(
                'label' => 'Titre',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
                'placeholder' => 'aucune',
                'empty_data'  => null,
            ))
            ->add('surname', 'choice', array(
                'label'    => 'Nom',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
            ))
            ->add('name', 'choice', array(
                'label'    => 'Prénom',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
            ))
            ->add('email', 'choice', array(
                'label'    => 'E-mail',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
            ))
            ->add('birthday', 'choice', array(
                'label'    => 'Date de naissance',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
                'placeholder' => 'aucune',
                'empty_data'  => null,
            ))
            ->add('birthplace', 'choice', array(
                'label'    => 'Lieu de naissance',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
                'placeholder' => 'aucune',
                'empty_data'  => null,
            ))
            ->add('phone', 'choice', array(
                'label'    => 'Téléphone',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
                'placeholder' => 'aucune',
                'empty_data'  => null,
            ))
            ->add('ranking', 'choice', array(
                'label'    => 'Classement ECN',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
                'placeholder' => 'aucune',
                'empty_data'  => null,
            ))
            ->add('graduate', 'choice', array(
                'label'    => 'Année ECN',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => $this->choices,
                'placeholder' => 'aucune',
                'empty_data'  => null,
            ))
            ->add('grade', 'entity', array(
                'label'    => 'Promotion',
                'required' => true,
                'class'    => 'GessehUserBundle:Grade',
            ))
            ->add('Envoyer', 'submit')
        ;
    }

    public function getName()
    {
        return 'gesseh_userbundle_importtype';
    }

}
