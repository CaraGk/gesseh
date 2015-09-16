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

use Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Request,
    Doctrine\ORM\EntityManager,
    Gesseh\RegisterBundle\Entity\Membership;

/**
 * JoinType Handler
 */
class JoinHandler
{
    private $form, $request, $em, $um, $payment, $token;

    public function __construct(Form $form, Request $request, EntityManager $em, $payment, $student)
    {
      $this->form    = $form;
      $this->request = $request;
      $this->em      = $em;
      $this->payment = $payment;
      $this->student = $student;
    }

    public function process()
    {
        if($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            if($this->form->isValid()) {
                $this->onSuccess($this->form->getData());

                return true;
            }
        }

        return false;
    }

    public function onSuccess(Membership $membership)
    {
        $membership->setPayment($this->payment);
        $membership->setExpiredOn((new \DateTime())->modify('+ 1 year'));
        $membership->setStudent($this->student);

        $this->em->persist($membership);
        $this->em->flush();
    }
}
