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
use Symfony\Bridge\Doctrine\Form\Type\EntityType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * PlacementType
 */
class PlacementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('student', EntityType::class, array(
            'class'         => 'GessehUserBundle:Student',
            'query_builder' => function (\Gesseh\UserBundle\Entity\StudentRepository $er) {
                return $er->createQueryBuilder('s')
                          ->addOrderBy('s.surname', 'ASC')
                          ->addOrderBy('s.name', 'ASC');
            },
        ))
                ->add('repartition')
                ->add('Enregistrer', SubmitType::class)
        ;
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
