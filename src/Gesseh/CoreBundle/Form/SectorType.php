<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * SectorType
 */
class SectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => ' ',
        ));
    }

    public function getName()
    {
        return 'gesseh_corebundle_sectortype';
    }
}
