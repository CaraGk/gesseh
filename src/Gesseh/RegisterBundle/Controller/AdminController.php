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
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
        $view = $this->get('request')->query->get('view', null);
        $membership = $em->getRepository('GessehRegisterBundle:Membership')->find($id);

        if (!$membership or $membership->getPayedOn() != null)
            throw $this->createNotFoundException('Unable to find Membership entity');

        $membership->setPayedOn(new \DateTime('now'));
        $em->persist($membership);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Paiement validé !');

        if ($view == 'index')
            return $this->redirect($this->generateUrl('GRegister_AIndex'));
        else
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
        $view = $this->get('request')->query->get('view', null);
        $membership = $em->getRepository('GessehRegisterBundle:Membership')->find($id);

        if (!$membership or $membership->getPayedOn() != null)
            throw $this->createNotFoundException('Unable to find Membership entity');

        $em->remove($membership);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Adhésion supprimée !');

        if ($view == 'index')
            return $this->redirect($this->generateUrl('GRegister_AIndex'));
        else
            return $this->redirect($this->generateUrl('GRegister_UIndex', array('userid' => $userid)));
    }

    /**
     * List active memberships
     *
     * @Route("/", name="GRegister_AIndex")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $limit = $this->get('request')->query->get('limit', null);
        $memberships = $em->getRepository('GessehRegisterBundle:Membership')->getCurrentForAll($limit);

        return array(
            'memberships' => $memberships,
            'limit'       => $limit,
        );
    }

    /**
     * Export active memberships
     *
     * @Route("/export", name="GRegister_AExport")
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $memberships = $em->getRepository('GessehRegisterBundle:Membership')->getCurrentForAllComplete();

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("GESSEH")
                       ->setTitle("Listing adhérents")
                       ->setSubject("Listing adhérents GESSEH");
        $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('Adherents');

        $i = 1;
        foreach ($memberships as $membership) {
            $address = $membership->getStudent()->getAddress();
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $membership->getStudent()->getTitle())
                ->setCellValue('B'.$i, $membership->getStudent()->getSurname())
                ->setCellValue('C'.$i, $membership->getStudent()->getName())
                ->setCellValue('D'.$i, $membership->getStudent()->getBirthday())
                ->setCellValue('E'.$i, $membership->getStudent()->getBirthplace())
                ->setCellValue('F'.$i, $membership->getStudent()->getPhone())
                ->setCellValue('G'.$i, $membership->getStudent()->getUser()->getEmail())
                ->setCellValue('H'.$i, $address['number'])
                ->setCellValue('I'.$i, $address['type'])
                ->setCellValue('J'.$i, $address['street'])
                ->setCellValue('K'.$i, $address['complement'])
                ->setCellValue('L'.$i, $address['code'])
                ->setCellValue('M'.$i, $address['city'])
                ->setCellValue('N'.$i, $address['country'])
                ->setCellValue('O'.$i, $membership->getStudent()->getRanking())
                ->setCellValue('P'.$i, $membership->getStudent()->getGraduate());
            $count = 0;
            foreach ($membership->getStudent()->getPlacements() as $placement) {
                if ($placement->getPeriod()->getEnd() < new \DateTime('now')) {
                    $count++;
                }
            }
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('Q'.$i, $count);
        }

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'adherents.xls');
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}
