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
use JMS\DiExtraBundle\Annotation as DI;

class PaymentController extends Controller
{
    /** @DI\Inject */
    private $session;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("fos_user.user_manager") */
    private $um;

    /** @DI\Inject("kdb_parameters.manager") */
    private $pm;

    /**
     * Prepare action
     *
     * @Route("/user/{memberid}/payment/{gateway}", name="GRegister_PPrepare", requirements={"memberid" = "\d+", "gateway" = "\w+"})
     */
    public function prepareAction($gateway, $memberid)
    {
        $user = $this->um->findUserByUsername($this->get('security.token_storage')->getToken()->getUsername());
        $membership = $this->em->getRepository('GessehRegisterBundle:Membership')->find($memberid);

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
        $payment->setTotalAmount($this->pm->findParamByName('reg_payment')->getValue() * 100);
        $payment->setDescription('Adhésion de ' . $user->getEmail() . ' via ' . $gateway);
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
                $this->addFlash('warning', 'Demande d\'adhésion enregistrée. L\'adhésion ne pourra être validée qu\'une fois le paiement reçu.');
                $this->addFlash('notice', 'Pour un paiement par chèque : le chèque de ' . $membership->getAmount() . ' euros est à libeller à l\'ordre de ' . $this->pm->findParamByName('reg_order')->getValue() . ' et à retourner à l\'adresse ' . $structure()->getPrintableAddress() . '.');
                $this->addFlash('notice', 'Pour un paiement par virement : veuillez contacter la structure pour effectuer le virement.');
            } else {
                $membership = $this->em->getRepository('GessehRegisterBundle:Membership')->find($payment->getClientId());
                $membership->setPayedOn(new \DateTime('now'));
                $membership->setPayment($payment);

                $this->em->persist($membership);
                $this->em->flush();

                $this->addFlash('notice', 'Le paiement de ' . $membership->getAmount() . ' euros par Paypal Express a réussi. L\'adhésion est validée.');
            }
        } else {
             $this->addFlash('error', 'Le paiement a échoué.');
        }
        return $this->redirect($this->generateUrl('GRegister_UIndex'));
    }
}
