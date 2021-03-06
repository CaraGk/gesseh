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
    FOS\UserBundle\Doctrine\UserManager;
use Gesseh\RegisterBundle\Entity\Membership,
    Gesseh\UserBundle\Entity\Student;

/**
 * RegisterType Handler
 */
class RegisterHandler
{
    private $form, $request, $em, $um, $token;

    public function __construct(Form $form, Request $request, EntityManager $em, UserManager $um, $token)
    {
      $this->form    = $form;
      $this->request = $request;
      $this->em      = $em;
      $this->um      = $um;
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

    public function onSuccess(Student $student)
    {
        $student->setAnonymous(false);

        $user = $student->getUser();
        $user->addRole('ROLE_STUDENT');
        $user->setConfirmationToken($this->token);

        $this->em->persist($student);
        $this->um->createUser();
        $this->um->updateUser($user);

        $this->em->flush();

        return $user->getUsername();
    }
}
