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
    Gesseh\RegisterBundle\Entity\Membership,
    FOS\UserBundle\Doctrine\UserManager;

/**
 * RegisterType Handler
 */
class RegisterHandler
{
    private $form, $request, $em, $um, $payment, $token;

    public function __construct(Form $form, Request $request, EntityManager $em, UserManager $um, $payment, $token)
    {
      $this->form    = $form;
      $this->request = $request;
      $this->em      = $em;
      $this->um      = $um;
      $this->payment = $payment;
      $this->token   = $token;
    }

    public function process()
    {
        if($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            if($this->form->isValid()) {
                $username = $this->onSuccess($this->form->getData());

                return $username;
            }
        }

        return false;
    }

    public function onSuccess(Membership $membership)
    {
        $membership->setPayment($this->payment);
        $membership->setExpiredOn((new \DateTime())->modify('+ 1 year'));

        $student = $membership->getStudent();
        $student->setAnonymous(false);

        $user = $student->getUser();
        $user->addRole('ROLE_STUDENT');
        $user->setConfirmationToken($this->token);

        $this->em->persist($membership);
        $this->em->persist($student);
        $this->um->createUser();
        $this->um->updateUser($user);

        $this->em->flush();

        return $user->getUsername();
    }
}
