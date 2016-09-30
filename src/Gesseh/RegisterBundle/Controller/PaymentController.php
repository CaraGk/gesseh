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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI,
    JMS\SecurityExtraBundle\Annotation as Security;
use Gesseh\RegisterBundle\Entity\Gateway,
    Gesseh\RegisterBundle\Form\GatewayType,
    Gesseh\RegisterBundle\Form\GatewayHandler;

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
            $method = $this->em->getRepository('GessehRegisterBundle:Gateway')->findOneBy(array('gatewayName' => $token->getGatewayName()));
            $membership = $this->em->getRepository('GessehRegisterBundle:Membership')->find($payment->getClientId());

            if ($method->getFactoryName() == 'offline') {
                $config = $method->getConfig();
                $address = $config['address']['number'] . ' ' . $config['address']['type'] . ' ' . $config['address']['street'];
                if ($config['address']['complement'])
                    $address .= ', ' . $config['address']['complement'];
                $address .= ', ' . $config['address']['code'] . ', ' . $config['address']['city'] . ', ' . $config['address']['country'];

                $this->addFlash('warning', 'Demande d\'adhésion enregistrée. L\'adhésion ne pourra être validée qu\'une fois le paiement reçu.');
                $this->addFlash('notice', 'Pour un paiement par chèque : le chèque de ' . $membership->getAmount() . ' euros est à libeller à l\'ordre de ' . $config['payableTo'] . ' et à retourner à l\'adresse ' . $address . '.');
                $this->addFlash('notice', 'Pour un paiement par virement : veuillez contacter la structure pour effectuer le virement.');
            } elseif ($method->getFactoryName() == 'paypal_express_checkout') {
                $membership->setPayedOn(new \DateTime('now'));
                $membership->setPayment($payment);
                $membership->setMethod($method);

                $this->em->persist($membership);
                $this->em->flush();

                $this->addFlash('notice', 'Le paiement de ' . $membership->getAmount() . ' euros par Paypal Express a réussi. L\'adhésion est validée.');
            }
        } else {
             $this->addFlash('error', 'Le paiement a échoué.');
        }
        return $this->redirect($this->generateUrl('GRegister_UIndex'));
    }

    /**
     * Show gateways
     *
     * @Route("/admin/gateway/index", name="GRegister_PIndex")
     * @Template()
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        $gateways = $this->em->getRepository('GessehRegisterBundle:Gateway')->findAll();

        if (!$gateways)
            throw $this->createNotFoundException('Impossible de trouver une Gateway');

        return array(
            'gateways' => $gateways,
        );
    }

    /**
     * Add a new gateway
     *
     * @Route("/admin/gateway/new", name="GRegister_PNew")
     * @Template("GessehRegisterBundle:Payment:edit.html.twig")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function newAction(Request $request)
    {
        $gateway = new Gateway();
        $form = $this->createForm(GatewayType::class, $gateway);
        $formHandler = new GatewayHandler($form, $request, $this->em);

        if ($formHandler->process()) {
            $this->session->getFlashBag()->add('notice', 'Moyen de paiement "' . $gateway . '" enregistré.');
            return $this->redirect($this->generateUrl('GRegister_PIndex'));
        }

        return array(
            'form'    => $form->createview(),
            'gateway' => null,
        );
    }

    /**
     * Edit a gateway
     *
     * @Route("/admin/gateway/{id}/edit", name="GRegister_PEdit", requirements={"id" = "\d+"})
     * @Template("GessehRegisterBundle:Payment:edit.html.twig")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function editAction(Gateway $gateway, Request $request)
    {
        $form = $this->createForm(GatewayType::class, $gateway);
        $formHandler = new GatewayHandler($form, $request, $this->em);

        if ($formHandler->process()) {
            $this->session->getFlashBag()->add('notice', 'Moyen de paiement "' . $gateway . '" modifié.');
            return $this->redirect($this->generateUrl('GRegister_PIndex'));
        }

        return array(
            'form'    => $form->createview(),
            'gateway' => $gateway,
        );
    }

    /**
     * Delete a gateway
     *
     * @Route("/admin/gateway/{id}/delete", name="GRegister_PDelete", requirements={"id" = "\d+"})
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Gateway $gateway)
    {
        $this->em->remove($gateway);
        $this->em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Moyen de paiement "' . $gateway . '" supprimé.');
        return $this->redirect($this->generateUrl('GRegister_PIndex'));
    }
}
