<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licences/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Request,
    Doctrine\ORM\EntityManager;
use Gesseh\CoreBundle\Entity\Repartition;

/**
 * Repartitions Type Handler
 */
class RepartitionsHandler
{
    private $form;
    private $request;
    private $em;
    private $repartitions;

    public function __construct(Form $form, Request $request, EntityManager $em, array $repartitions)
    {
        $this->form         = $form;
        $this->request      = $request;
        $this->em           = $em;
        $this->repartitions = $repartitions;
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

    public function onSuccess($data)
    {
        foreach($data['Repartitions'] as $repartition) {
            $this->em->persist($repartition);
        }
        $this->em->flush();
    }
}
