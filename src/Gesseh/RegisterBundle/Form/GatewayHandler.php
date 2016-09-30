<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\RegisterBundle\Entity\Gateway;

/**
 * GatewayType Handler
 */
class GatewayHandler
{
    private $form;
    private $request;
    private $em;

    public function __construct(Form $form, Request $request, EntityManager $em)
    {
        $this->form      = $form;
        $this->request   = $request;
        $this->em        = $em;
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

    public function onSuccess(Gateway $gateway)
    {
        $gateway->setGatewayName($gateway->getFactoryName());
        $this->em->persist($gateway);
        $this->em->flush();
    }
}

