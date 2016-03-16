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
    Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Repartitions Type
 */
class RepartitionsType extends AbstractType
{
    private $repartitions;
    private $type;

    public function __construct($repartitions, $type = 'period')
    {
        $this->repartitions = $repartitions;
        $this->type         = $type;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Repartitions', 'collection', array(
            'type' => new RepartitionType($this->type),
            'data' => $this->repartitions,
        ));
        $builder->add('Enregistrer et passer aux suivants', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }
}
