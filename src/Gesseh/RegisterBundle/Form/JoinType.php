<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * JoinType
 */
class JoinType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('method', EntityType::class, array(
                'class' => 'Gesseh\RegisterBundle\Entity\Gateway',
                'choice_label' => 'description',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label'    => 'Moyen de paiement'
            ))
            ->add('Payer', SubmitType::class)
        ;
    }

    public function getName()
    {
        return 'gesseh_registerbundle_jointype';
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\RegisterBundle\Entity\Membership',
        ));
    }
}
