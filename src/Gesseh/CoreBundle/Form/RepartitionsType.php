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
use Gesseh\CoreBundle\Form\RepartitionType;

/**
 * Repartitions Type
 */
class RepartitionsType extends AbstractType
{
    private $repartitions;
    private $type;

    public function __construct(array $repartitions, $type = 'period')
    {
        $this->repartitions = $repartitions;
        $this->type         = $type;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach($this->repartitions as $repartition) {
            $id = $repartition->getId();

            if($this->type == 'period') {
                $builder->add('department_' . $id, 'entity', array(
                            'class'    => 'Gesseh\CoreBundle\Entity\Department',
                            'disabled' => true,
                            'data'     => $repartition->getDepartment(),
                            'label'    => 'Service',
                        ))
                ;
            } elseif($this->type == 'department') {
                $builder->add('period_' . $id, 'entity', array(
                            'class'    => 'Gesseh\CoreBundle\Entity\Period',
                            'disabled' => true,
                            'data'     => $repartition->getPeriod(),
                            'label'    => 'Période de stage',
                        ))
                ;
            }

            $builder->add('number_' . $id, 'integer', array(
                        'required' => true,
                        'data'     => $repartition->getNumber(),
                        'label'    => 'Postes ouverts',
                    ))
                    ->add('cluster_' . $id, 'text', array(
                        'required' => false,
                        'data'     => $repartition->getCluster(),
                        'label'    => 'Stage couplé',
                    ))
            ;
        }
        $builder->add('Enregistrer', 'submit');
    }

    public function getName()
    {
        return 'gesseh_corebundle_repartitionstype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }
}