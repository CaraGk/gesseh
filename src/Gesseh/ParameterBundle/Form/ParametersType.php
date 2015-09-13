<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\ParameterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * ParametersType
 */
class ParametersType extends AbstractType
{
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->parameters as $parameter) {
            if ($parameter->getType() == "string") {
                $builder->add($parameter->getName(), 'text', array(
                    'required' => false,
                    'label'    => $parameter->getLabel(),
                    'data'     => $parameter->getValue(),
                ));
            } elseif ($parameter->getType() == "boolean") {
                $builder->add($parameter->getName(), 'checkbox', array(
                    'required' => false,
                    'value'    => false,
                    'label'    => $parameter->getLabel(),
                    'data'     => (bool) $parameter->getValue(),
                ));
            }
        }
        $builder->add('Enregistrer', 'submit');
    }

  public function getName()
  {
    return 'gesseh_parameterbundle_parameterstype';
  }
}
