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
    public function prepareAction($gateway)
    {
        $storage = $this->get('payum')->getStorage('Gesseh\RegisterBundle\Entity\Payment');

        $payment = $storage->create();

        if ($gateway == 'offline') {
            $payment->setNumber(uniqid());
            $payment->setCurrencyCode('EUR');
            $payment->setTotalAmount(60);
            $payment->setDescription('Adhésion');
            $payment->setClientId();
            $payment->setClientEmail();
        } elseif ($gateway == 'paypal') {
            $payment['PAYMENTREQUEST_0_CURRENCYCODE'] = 'EUR';
            $payment['PAYMENTREQUEST_0_AMT'] = 1.23;
        }

        $storage->update($payment);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gateway,
            $payment,
            'done'
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    public function doneAction(Request $resquest)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        return $this->redirect('GRegister_UIndex');
    }
}
