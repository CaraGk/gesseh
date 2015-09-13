<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * EvalSectorType
 */
class EvalSectorType extends AbstractType
{
  private $exclude_sectors;

  public function __construct($exclude_sectors, $eval_form)
  {
    $this->exclude_sectors = $exclude_sectors;
    $this->eval_form = $eval_form;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $exclude_sectors = $this->exclude_sectors;

    $builder
        ->add('sector', 'entity', array(
            'class'         => 'GessehCoreBundle:Sector',
            'query_builder' => function (\Gesseh\CoreBundle\Entity\SectorRepository $er) use ($exclude_sectors) { return $er->listOtherSectors($exclude_sectors); },
            'label'         => 'Lier une catégorie de stage : ',
        ))
        ->add('form', 'hidden', array(
            'data' => $this->eval_form->getId(),
        ))
        ->add('Enregistrer', 'submit')
    ;
  }

  public function getName()
  {
    return 'gesseh_evaluationbundle_evalsectortype';
  }

  public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\EvaluationBundle\Entity\EvalSector',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
