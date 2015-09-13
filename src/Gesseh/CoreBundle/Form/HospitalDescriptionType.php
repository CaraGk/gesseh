<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * HospitalDescriptionType
 */
class HospitalDescriptionType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('description', 'textarea', array(
      'attr' => array(
        'class'      => 'tinymce',
        'data-theme' => 'medium',
      ),
    ));
  }

  public function getName()
  {
    return 'gesseh_corebundle_hospitaltype';
  }

  public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Gesseh\CoreBundle\Entity\Hospital',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
