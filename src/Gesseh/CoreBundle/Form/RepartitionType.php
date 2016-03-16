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
    Symfony\Component\OptionsResolver\OptionsResolver;
use Gesseh\CoreBundle\Form\RepartitionType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType,
    Symfony\Component\Form\Extension\Core\Type\IntegerType,
    Symfony\Component\Form\Extension\Core\Type\TextType,
    Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Repartition Type
 */
class RepartitionType extends AbstractType
{
    private $type;

    public function __construct($type = 'period')
    {
        $this->type         = $type;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->type == 'period') {
            $builder->add('department', null, array(
                'disabled' => true,
                'label'    => 'Service',
                ))
            ;
        } elseif($this->type == 'department') {
            $builder->add('period', null, array(
                'disabled' => true,
                'label'    => 'Période de stage',
                ))
            ;
        }

        $builder->add('number', null, array(
            'required' => true,
            'label'    => 'Postes ouverts',
            ))
                ->add('cluster', null, array(
            'required' => false,
            'label'    => 'Stage couplé',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\CoreBundle\Entity\Repartition',
        ));
    }
}
