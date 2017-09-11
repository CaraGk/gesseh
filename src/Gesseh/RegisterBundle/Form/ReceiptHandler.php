<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\RegisterBundle\Entity\Receipt;

/**
 * ReceiptType Handler
 */
class ReceiptHandler
{
    private $form, $request, $em;

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

    public function onSuccess(Receipt $receipt)
    {
        $receipt->setUpdatedAt(new \DateTime('now'));
        $this->em->persist($receipt);
        $this->em->flush();
    }
}
