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
    Symfony\Bridge\Doctrine\Form\Type\EntityType,
    Symfony\Component\Form\Extension\Core\Type\FileType,
    Symfony\Component\Form\Extension\Core\Type\TextType,
    Symfony\Component\Form\Extension\Core\Type\DateType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Gesseh\RegisterBundle\Entity\Membership;

/**
 * ReceiptType
 */
class ReceiptType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('begin', DateType::class, array(
                'label' => 'Du',
            ))
            ->add('end', DateType::class, array(
                'label' => 'Au',
            ))
            ->add('student', EntityType::class, array(
                'label'         => 'Signataire',
                'class'         => 'GessehUserBundle:Student',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->join('\Gesseh\RegisterBundle\Entity\Membership', 'm', Join::WITH, 's.id = m.student');
                },
                'required'      => true,
                'multiple'      => false,
                'expanded'      => false,
            ))
            ->add('position', TextType::class, array(
                'label' => 'En qualité de'
            ))
            ->add('image', VichImageType::class, array(
                'label'         => 'Signature (image)',
                'required'      => false,
                'allow_delete'  => true,
                'download_link' => false,
            ))
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function getName()
    {
        return 'gesseh_registerbundle_receipttype';
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\RegisterBundle\Entity\Receipt',
        ));
    }
}
