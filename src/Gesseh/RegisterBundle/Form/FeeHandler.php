<?php

/**
 * This file is part of PIGASS project
 *
 * @author: Pierre-François ANGRAND <pigass@medlibre.fr>
 * @copyright: Copyright 2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Pigass\CoreBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Pigass\CoreBundle\Entity\Fee,
    Pigass\CoreBundle\Entity\Structure;

/**
 * FeeType Handler
 */
class FeeHandler
{
    private $form, $request, $em, $structure;

    public function __construct(Form $form, Request $request, EntityManager $em, Structure $structure)
    {
        $this->form      = $form;
        $this->request   = $request;
        $this->em        = $em;
        $this->structure = $structure;
    }

    public function process()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->form->handleRequest($this->request);

            if ($this->form->isSubmitted() and $this->form->isValid()) {
                $this->onSuccess($this->form->getData());

                return true;
            }
        }

        return false;
    }

    public function onSuccess(Fee $fee)
    {
        $fee->setStructure($this->structure);
        if ($fee->isDefault()) {
            $other_fees = $this->em->getRepository('PigassCoreBundle:Fee')->findBy(['default' => true, 'structure' => $this->structure]);
            foreach ($other_fees as $other_fee) {
                $other_fee->setDefault(false);
                $this->em->persist($other_fee);
            }
        }
        $this->em->persist($fee);
        $this->em->flush();
    }
}
