<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\RegisterBundle\Entity\Membership;

/**
 * RegisterBundle AdminController
 *
 * @Route("/admin/membership")
 */
class AdminController extends Controller
{
    /**
     * Validate payment
     *
     * @Route("/{id}/validate", name="GRegister_AValidate", requirements={"id" = "\d+"})
     */
    public function validateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userid = $this->get('request')->query->get('userid', null);
        $membership = $em->getRepository('GessehRegisterBundle:Membership')->find($id);

        if (!$membership or $membership->getPayedOn() != null)
            throw $this->createNotFoundException('Unable to find Membership entity');

        $membership->setPayedOn(new \DateTime('now'));
        $em->persist($membership);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Paiement validé !');

        return $this->redirect($this->generateUrl('GRegister_UIndex', array('userid' => $userid)));
    }

    /**
     * Delete membership
     *
     * @Route("/{id}/delete", name="GRegister_ADelete", requirements={"id" = "\d+"})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userid = $this->get('request')->query->get('userid', null);
        $membership = $em->getRepository('GessehRegisterBundle:Membership')->find($id);

        if (!$membership or $membership->getPayedOn() != null)
            throw $this->createNotFoundException('Unable to find Membership entity');

        $em->remove($membership);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Adhésion supprimée !');

        return $this->redirect($this->generateUrl('GRegister_UIndex', array('userid' => $userid)));
    }
}
