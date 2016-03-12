<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\UserBundle\Entity\Student;
use FOS\UserBundle\Doctrine\UserManager;

/**
 * StudentType Handler
 */
class StudentHandler
{
    private $form;
    private $request;
    private $em;
    private $um;

    public function __construct(Form $form, Request $request, EntityManager $em, UserManager $um, $admin = false)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->em      = $em;
        $this->um      = $um;
        $this->admin   = $admin;
    }

    public function process()
    {
        if ( $this->request->getMethod() == 'POST' ) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess(($this->form->getData()));

                return true;
            }
        }

        return false;
    }

    public function onSuccess(Student $student)
    {
        $this->updateUser($student->getUser());
        $student->setAnonymous(false);
        $this->em->persist($student);
        $this->em->flush();
    }

    private function updateUser($user)
    {
        if (null == $user->getId()) {
            $this->um->createUser();
            if (!$user->getPlainPassword()) {
                $user->setPlainPassword($this->generatePwd(8));
            } else {
                $user->addRole('ROLE_ADMIN');
            }
            if ($this->admin) {
                $user->addRole('ROLE_ADMIN');
            }
            $user->setConfirmationToken(null);
            $user->setEnabled(true);
            $user->addRole('ROLE_STUDENT');
        }
        $user->setUsername($user->getEmail());

        $this->um->updateUser($user);
    }

    private function generatePwd($length)
    {
        $characters = array ('a','z','e','r','t','y','u','p','q','s','d','f','g','h','j','k','m','w','x','c','v','b','n','2','3','4','5','6','7','8','9','A','Z','E','R','T','Y','U','P','S','D','F','G','H','J','K','L','M','W','X','C','V','B','N');
        $password = '';

        for ($i = 0 ; $i < $length ; $i++) {
            $rand = array_rand($characters);
            $password .= $characters[$rand];
        }

        return $password;
    }
}
