<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Bridge\Doctrine\Form\Type\EntityType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Gesseh\UserBundle\Form\UserType;

/**
 * Accreditation Type
 */
class AccreditationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('begin')
                ->add('end')
                ->add('sector', EntityType::class, array(
                    'required' => true,
                    'label'    => 'Agrément',
                    'class'    => 'Gesseh\CoreBundle\Entity\Sector',
                ))
                ->add('supervisor')
                ->add('user', new UserType('Gesseh\UserBundle\Entity\User'), array(
                    'label' => ' ',
                ))
                ->add('comment')
                ->add('Enregister', SubmitType::class)
        ;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\CoreBundle\Entity\Accreditation',
        ));

        $resolver->setAllowedValues(array(
        ));
    }
}
