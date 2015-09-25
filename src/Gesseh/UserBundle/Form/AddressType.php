<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 * AddressType
 */
class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', 'integer', array(
                'label' => 'Nº dans la voie',
            ))
            ->add('type', 'choice', array(
                'label' => 'Type de voie',
                'choices' => array(
                    'allée' => 'allée',
                    'avenue' => 'avenue',
                    'boulevard' => 'boulevard',
                    'bourg' => 'bourg',
                    'chemin' => 'chemin',
                    'cité ' => 'cité ',
                    'clos' => 'clos',
                    'cote' => 'cote',
                    'cours' => 'cours',
                    'domaine' => 'domaine',
                    'espace' => 'espace',
                    'esplanade' => 'esplanade',
                    'faubourg' => 'faubourg',
                    'grande rue' => 'grande rue',
                    'impasse' => 'impasse',
                    'lieu dit' => 'lieu dit',
                    'lot' => 'lot',
                    'montée' => 'montée',
                    'parvis' => 'parvis',
                    'passage' => 'passage',
                    'pavillon' => 'pavillon',
                    'place' => 'place',
                    'plan' => 'plan',
                    'quai' => 'quai',
                    'résidence' => 'résidence',
                    'route' => 'route',
                    'rue' => 'rue',
                    'ruelle' => 'ruelle',
                    'square' => 'square',
                    'ter' => 'ter',
                    'traverse' => 'traverse',
                ),
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('street', 'text', array(
                'label' => 'Nom de voie',
            ))
            ->add('complement', 'text', array(
                'label' => 'Complément d\'adresse',
            ))
            ->add('code', 'integer', array(
                'label' => 'Code postal',
            ))
            ->add('city', 'text', array(
                'label' => 'Ville',
            ))
            ->add('country', 'country', array(
                'label' => 'Pays',
            ))
        ;
    }

    public function getName()
    {
        return 'gesseh_userbundle_addresstype';
    }

}
