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
 * PlacementType
 */
class PlacementType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('period')
            ->add('student', 'entity', array(
              'class'         => 'GessehUserBundle:Student',
              'query_builder' => function (\Gesseh\UserBundle\Entity\StudentRepository $er) {
                return $er->createQueryBuilder('s')
                  ->addOrderBy('s.surname', 'ASC')
                  ->addOrderBy('s.name', 'ASC');
              },
            ))
            ->add('department');
  }

  public function getName()
  {
    return 'gesseh_corebundle_placementtype';
  }

  public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'Gesseh\CoreBundle\Entity\Placement',
    ));

    $resolver->setAllowedValues(array(
    ));
  }
}
