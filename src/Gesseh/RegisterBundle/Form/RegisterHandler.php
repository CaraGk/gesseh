<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Form;

use Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Request,
    Doctrine\ORM\EntityManager,
    Gesseh\RegisterBundle\Entity\Membership,
    FOS\UserBundle\Doctrine\UserManager;

/**
 * RegisterType Handler
 */
class RegisterHandler
{
    private $form;
    private $request;
    private $em;
    private $um;

    public function __construct(Form $form, Request $request, EntityManager $em, UserManager $um, $payment)
    {
      $this->form    = $form;
      $this->request = $request;
      $this->em      = $em;
      $this->um      = $um;
      $this->payment = $payment;
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
        $this->updateUser($membership->getStudent()->getUser());
        $membership->getStudent()->setAnonymous(false);
        $membership->setPayment($this->payment);
        $membership->setExpiredOn((new \DateTime())->modify('+ 1 year'));
        $this->em->persist($membership);
        $this->em->flush();
    }

    private function updateUser($user)
    {
        if(null == $user->getUsername()) {
            $this->um->createUser();
            $user->setEnabled('false');
            $user->addRole('ROLE_STUDENT');
        }
        $user->setUsername($user->getEmail());

        $this->um->updateUser($user);
    }
}
