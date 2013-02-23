<?php

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('rank')
                ->add('isActive')
        ;
    }

    public function getName()
    {
        return 'gesseh_userbundle_gradetype';
    }
}
