<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    /**
     * Prepare action
     *
     * @Route("/user/{memberid}/payment/{gateway}", name="GRegister_PPrepare", requirements={"memberid" = "\d+", "gateway" = "\w+"})
     */
    public function prepareAction($gateway, $memberid)
    {
        $em = $this->getDoctrine()->getManager();
        $pm = $this->container->get('kdb_parameters.manager');
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserByUsername($this->get('security.token_storage')->getToken()->getUsername());
        $membership = $em->getRepository('GessehRegisterBundle:Membership')->find($memberid);

        if (!$membership or $membership->getStudent()->getUser() !== $user)
            throw $this->createNotFoundException('Impossible d\'effectuer la transaction. Contactez un administrateur.');

        if ($gateway == 1)
            $gateway = 'offline';
        elseif ($gateway == 2)
            $gateway = 'paypal';

        $storage = $this->get('payum')->getStorage('Gesseh\RegisterBundle\Entity\Payment');

        $payment = $storage->create();

        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount(60.00);
        $payment->setDescription('Adhésion');
        $payment->setClientId($memberid);
        $payment->setClientEmail($user->getEmail());

        $storage->update($payment);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gateway,
            $payment,
            'GRegister_PDone'
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    /**
     * Done transaction action
     *
     * @Route("/user/payment/valid", name="GRegister_PDone")
     */
    public function doneAction(Request $request)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        if ($status->isCaptured()) {
            if ($gateway == 'offline') {
                 $this->addFlash('warning', 'Choix enregistré. L\'adhésion sera validée un fois le chèque reçu.')
            } else {
                $this->addFlash('notice', 'Le paiement a réussi. L\'adhésion est validée.');
            }
        } else {
             $this->addFlash('error', 'Le paiement a échoué.');
        }
        return $this->redirect($this->generateUrl('GRegister_UIndex'));
    }
}
