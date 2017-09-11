<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI,
    JMS\SecurityExtraBundle\Annotation as Security;
use Gesseh\RegisterBundle\Entity\Receipt,
    Gesseh\RegisterBundle\Form\ReceiptType,
    Gesseh\RegisterBundle\Form\ReceiptHandler,
    Gesseh\RegisterBundle\Entity\Membership;

/**
 * Receipt controller.
 *
 * @Route("/")
 */
class ReceiptController extends Controller
{
    /** @DI\Inject */
    private $session;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("kdb_parameters.manager") */
    private $pm;

    /** @DI\Inject("fos_user.user_manager") */
    private $um;

    /**
     * List the receipts for receipt
     *
     * @Route("/receipts", name="GRegister_RIndex")
     * @Security\Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function indexAction()
    {
        $receipts = $this->em->getRepository('GessehRegisterBundle:Receipt')->getAll();

        return array(
            'receipts'  => $receipts,
        );
    }

    /**
     * Add a new receipt
     *
     * @Route("/receipt/new", name="GRegister_RNew")
     * @Template("GessehRegisterBundle:Receipt:edit.html.twig")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function newAction(Request $request)
    {
        $receipt = new Receipt();
        $form = $this->createForm(ReceiptType::class, $receipt);
        $formHandler = new ReceiptHandler($form, $request, $this->em);

        if ($formHandler->process()) {
            $this->session->getFlashBag()->add('notice', 'Reçus fiscaux par "' . $receipt->getStudent() . '" enregistrés.');
            return $this->redirect($this->generateUrl('GRegister_RIndex'));
        }

        return array(
            'form'      => $form->createView(),
            'receipt'   => null,
        );
    }

    /**
     * Edit a receipt
     *
     * @Route("/receipt/{id}/edit", name="GRegister_REdit", requirements={"id" = "\d+"})
     * @Template("GessehRegisterBundle:Receipt:edit.html.twig")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function editAction(Receipt $receipt, Request $request)
    {
        if ($receipt->getImage()) {
            $receipt->setImage(new File($this->getParameter('logo_dir') . '/signs/' . $receipt->getImageName()));
        }
        $form = $this->createForm(ReceiptType::class, $receipt);
        $formHandler = new ReceiptHandler($form, $request, $this->em);

        if ($formHandler->process()) {
            $this->session->getFlashBag()->add('notice', 'Reçus fiscaux par "' . $receipt->getStudent() . '" modifiés.');
            return $this->redirect($this->generateUrl('GRegister_RIndex'));
        }

        return array(
            'form'    => $form->createView(),
            'receipt' => $receipt,
        );
    }

    /**
     * Delete a receipt
     *
     * @Route("/receipt/{id}/delete", name="GRegister_RDelete", requirements={"id" = "\d+"})
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Receipt $receipt)
    {
        $this->em->remove($receipt);
        $this->em->flush();

        $this->session->getFlashBag()->add('notice', 'Session "' . $receipt . '" supprimée.');
        return $this->redirect($this->generateUrl('GRegister_RIndex'));
    }

    /**
     * Build and download receipt for membership
     *
     * @Route("/member/{id}/receipt", name="GRegister_RBuild", requirements={"id" = "\d+"})
     * @Security\Secure(roles="ROLE_STUDENT")
     */
    public function buildAction(Membership $membership)
    {
        $user = $this->getUser();
        if (!$user->hasRole('ROLE_ADMIN')) {
            $student = $this->em->getRepository('GessehUserBundle:Student')->findOneBy(['user' => $user->getId()]);
            if ($membership->getStudent() !== $student) {
                $this->session->getFlashBag()->add('error', 'Vous n\'avez pas les autorisations pour accéder à cette adhésion.');
                return $this->redirect($this->generateUrl('GRegister_AIndex'));
            }
        }
        $receipt = $this->em->getRepository('GessehRegisterBundle:Receipt')->getOneByDate($membership->getExpiredOn());
        if (!$receipt) {
            $this->session->getFlashBag()->add('error', 'Aucun signataire de reçu fiscal défini. Contactez l\'administrateur.');
            return $this->redirect($this->generateUrl('GRegister_AIndex'));
        }

        $html = $this->renderView(
            'GessehRegisterBundle:Receipt:printPDF.html.twig',
            [
                'receipt'    => $receipt,
                'membership' => $membership,
            ]
        );
        $filename = "Recu_" . $membership->getStudent()->getName() . "_" . $membership->getExpiredOn()->format('Y');

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }
}
