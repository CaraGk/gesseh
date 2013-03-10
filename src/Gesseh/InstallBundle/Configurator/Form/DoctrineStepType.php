<?php

/**
 * This file is part of GESSEH project
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: (c) Fabien Potencier <fabien@symfony.com>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\InstallBundle\Configurator\Form;

use Gesseh\InstallBundle\Configurator\Step\DoctrineStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Doctrine Form Type.
 */
class DoctrineStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('driver', 'choice', array('choices' => DoctrineStep::getDrivers()))
            ->add('name', 'text')
            ->add('host', 'text')
            ->add('port', 'text', array('required' => false))
            ->add('user', 'text')
            ->add('password', 'repeated', array(
                'required'        => false,
                'type'            => 'password',
                'first_name'      => 'password',
                'second_name'     => 'password_again',
                'invalid_message' => 'The password fields must match.',
            ))
        ;
    }

    public function getName()
    {
        return 'installbundle_doctrine_step';
    }
}
