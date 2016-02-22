<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Request,
    Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager;
use Gesseh\CoreBundle\Entity\Accreditation,
    Gesseh\CoreBundle\Entity\Department;

/**
 * AccreditationType Handler
 */
class AccreditationHandler
{
    private $form;
    private $request;
    private $em;
    private $um;
    private $department;

    public function __construct(Form $form, Request $request, EntityManager $em, UserManager $um, Department $department = null)
    {
        $this->form       = $form;
        $this->request    = $request;
        $this->em         = $em;
        $this->um         = $um;
        $this->department = $department;
    }

    public function process()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess(($this->form->getData()));

                return true;
            }
        }

        return false;
    }

    public function onSuccess(Accreditation $accreditation)
    {
        if ($this->department != null) {
            $accreditation->setDepartment($this->department);
            if ($user = $this->um->findUserByEmail($accreditation->getUser()->getEmail())) {
                $user->addRole('ROLE_TEACHER');
                if ($user->isEnabled() == false) {
                    $user->setEnabled(true);
                    $user->setPlainPassword($this->generatePwd(8));
                }
                $this->um->updateUser($user);
                $accreditation->setUser($user);
            } else {
                $user = $accreditation->getUser();
                $user->addRole('ROLE_TEACHER');
                $user->setEnabled(true);
                $user->setConfirmationToken(null);
                $user->setUsername($user->getEmail());
                $user->setPlainPassword($this->generatePwd(8));
                $this->um->updateUser($user);
                $accreditation->setUser($user);
            }
        } else {
            $user = $accreditation->getUser();
            if ($user->isEnabled() == false) {
                $user->setEnabled(true);
                $user->setPlainPassword($this->generatePwd(8));
            }
            $user->setUsername($user->getEmail());
            $this->um->updateUser($user);
        }

        $this->em->persist($accreditation);
        $this->em->flush();
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

