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
    Gesseh\RegisterBundle\Entity\Membership;

/**
 * RegisterType Handler
 */
class RegisterHandler
{
    private $form;
    private $request;
    private $em;

    public function __construct(Form $form, Request $request, EntityManager $em)
    {
      $this->form    = $form;
      $this->request = $request;
      $this->em      = $em;
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
        $this->em->persist($membership);
        $this->em->flush();
    }
}
