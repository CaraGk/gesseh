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
    Gesseh\UserBundle\Form\StudentRegisterType;

/**
 * RegisterType
 */
class RegisterType extends AbstractType
{
    private $testSimulActive;

    public function __construct($testSimulActive = true)
    {
        $this->testSimulActive = $testSimulActive;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('student', new StudentRegisterType('Gesseh\UserBundle\Entity\Student', $this->testSimulActive), array(
                'label' => ' ',
            ))
            ->add('method', 'choice', array(
                'choices' => array(
                    '1' => 'Chèque',
                ),
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label'    => 'Moyen de paiement'
            ))
            ->add('Enregistrer', 'submit')
        ;
    }

    public function getName()
    {
        return 'gesseh_registerbundle_registertype';
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesseh\RegisterBundle\Entity\Membership',
        ));

        $resolver->setAllowedValues(array(
        ));
    }
}
