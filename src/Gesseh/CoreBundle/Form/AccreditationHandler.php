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
    private $department;

    public function __construct(Form $form, Request $request, EntityManager $em, Department $department = null)
    {
        $this->form       = $form;
        $this->request    = $request;
        $this->em         = $em;
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
        if ($this->department != null)
            $accreditation->setDepartment($department);

        $this->em->persist($accreditation);
        $this->em->flush();
    }
}

