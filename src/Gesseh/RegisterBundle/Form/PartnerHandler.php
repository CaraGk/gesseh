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
use FOS\UserBundle\Doctrine\UserManager;
use Gesseh\RegisterBundle\Entity\Partner;

/**
 * PartnerType Handler
 */
class PartnerHandler
{
    private $form, $request, $em, $um;

    public function __construct(Form $form, Request $request, EntityManager $em, UserManager $um)
    {
        $this->form      = $form;
        $this->request   = $request;
        $this->em        = $em;
        $this->um        = $um;
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

    public function onSuccess(Partner $partner)
    {
        $this->updateUser($partner->getUser());
        $this->em->persist($partner);
        $this->em->flush();
    }

    private function updateUser($user)
    {
        if (null == $user->getId()) {
            $this->um->createUser();
            if (!$user->getPlainPassword()) {
                $user->setPlainPassword($this->generatePwd(8));
            }
            $user->setConfirmationToken(null);
            $user->setEnabled(true);
            $user->addRole('ROLE_PARTNER');
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

