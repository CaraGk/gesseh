<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * EvalCriteriaType
 */
class EvalCriteriaType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('rank')
            ->add('name')
            ->add('type', 'choice', array(
              'choices' => array(
                '1' => 'Choix unique pondéré',
                '5' => 'Choix unique non pondéré',
                '7' => 'Échelle visuelle analogique',
                '3' => 'Choix multiple',
                '4' => 'Valeur numérique',
                '6' => 'Horaire',
                '2' => 'Texte long',
              ),
              'required' => true,
              'multiple' => false,
              'expanded' => false,
            ))
            ->add('more')
            ->add('required')
            ->add('moderate')
            ->add('private')
    ;
  }

  public function getName()
  {
    return 'gesseh_evaluationbundle_evalcriteriatype';
  }

  public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\EvaluationBundle\Entity\EvalCriteria',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
