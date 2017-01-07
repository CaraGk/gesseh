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
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI,
    JMS\SecurityExtraBundle\Annotation as Security;
use Gesseh\RegisterBundle\Entity\Membership;
use Gesseh\RegisterBundle\Form\FilterType,
    Gesseh\RegisterBundle\Form\FilterHandler;

/**
 * RegisterBundle MembershipController
 *
 * @Route("/")
 */
class MembershipController extends Controller
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
     * Validate payment
     *
     * @Route("/admin/membership/{id}/validate", name="GRegister_AValidate", requirements={"id" = "\d+"})
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function validateAction(Membership $membership, Request $request)
    {
        $userid = $request->query->get('userid', null);
        $view = $request->query->get('view', null);

        if (!$membership or $membership->getPayedOn() != null)
            throw $this->createNotFoundException('Unable to find Membership entity');

        $membership->setPayedOn(new \DateTime('now'));
        $this->em->persist($membership);
        $this->em->flush();

        $this->session->getFlashBag()->add('notice', 'Paiement validé !');

        if ($view == 'index')
            return $this->redirect($this->generateUrl('GRegister_AIndex'));
        else
            return $this->redirect($this->generateUrl('GRegister_UIndex', array('userid' => $userid)));
    }

    /**
     * Delete membership
     *
     * @Route("/user/membership/{id}/delete", name="GRegister_ADelete", requirements={"id" = "\d+"})
     * @Security\Secure(roles="ROLE_STUDENT, ROLE_ADMIN")
     */
    public function deleteAction(Membership $membership, Request $request)
    {
        $userid = $request->query->get('userid', null);
        $view = $request->query->get('view', null);

        if (!$membership or $membership->getPayedOn() != null)
            throw $this->createNotFoundException('Unable to find Membership entity');

        $this->em->remove($membership);
        $this->em->flush();

        $this->session->getFlashBag()->add('notice', 'Adhésion supprimée !');

        if ($view == 'index')
            return $this->redirect($this->generateUrl('GRegister_AIndex'));
        else
            return $this->redirect($this->generateUrl('GRegister_UIndex', array('userid' => $userid)));
    }

    /**
     * List active memberships
     *
     * @Route("/admin/membership", name="GRegister_AIndex")
     * @Template()
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit', null);
        $questions = $this->em->getRepository('GessehRegisterBundle:MemberQuestion')->findAll();
        $membership_filters = $this->session->get('gregister_membership_filter', array(
            'valid'     => null,
            'questions' => null,
        ));
        $memberships = $this->em->getRepository('GessehRegisterBundle:Membership')->getCurrentForAll($membership_filters);

        return array(
            'memberships' => $memberships,
            'filters'     => $membership_filters,
            'questions'   => $questions,
        );
    }

    /**
     * Export active memberships
     *
     * @Route("/admin/membership/export", name="GRegister_AExport")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function exportAction()
    {
        $memberships = $this->em->getRepository('GessehRegisterBundle:Membership')->getCurrentForAll();
        $sectors = $this->em->getRepository('GessehCoreBundle:Sector')->findAll();
        $memberquestions = $this->em->getRepository('GessehRegisterBundle:MemberQuestion')->findAll();
        $memberinfos = $this->em->getRepository('GessehRegisterBundle:MemberInfo')->getCurrentInArray();

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("GESSEH")
                       ->setTitle("Listing adhérents")
                       ->setSubject("Listing adhérents GESSEH");
        $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('Adherents');

        $i = 2;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Titre')
            ->setCellValue('B1', 'Nom')
            ->setCellValue('C1', 'Prénom')
            ->setCellValue('D1', 'Date de naissance')
            ->setCellValue('E1', 'Lieu de naissance')
            ->setCellValue('F1', 'Téléphone')
            ->setCellValue('G1', 'E-mail')
            ->setCellValue('H1', 'Nº')
            ->setCellValue('I1', 'Type')
            ->setCellValue('J1', 'Adresse')
            ->setCellValue('K1', 'Complément')
            ->setCellValue('L1', 'Code postal')
            ->setCellValue('M1', 'Ville')
            ->setCellValue('N1', 'Pays')
            ->setCellValue('O1', 'Ville d\'externat')
            ->setCellValue('P1', 'Rang de classement')
            ->setCellValue('Q1', 'ECN')
            ->setCellValue('R1', 'Stages validés')
            ;
        $column = array('S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AZ');
        foreach ($sectors as $sector) {
            $key = each($column);
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue($key['value'] . '1', $sector->getName());
            $columns[$sector->getName()] = $key['value'];
        }
        foreach ($memberquestions as $question) {
            $key = each($column);
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue($key['value'] . '1', $question->getName());
            $columns[$question->getName()] = $key['value'];
        }
        $key = each($column);
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue($key['value'] . '1', 'Mode de paiement');
        $columns['Mode de paiement'] = $key['value'];
        $key = each($column);
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue($key['value'] . '1', 'Date d\'adhésion');
        $columns['Date d\'adhésion'] = $key['value'];

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
                ->setCellValue('P'.$i, $membership->getStudent()->getRanking())
                ->setCellValue('Q'.$i, $membership->getStudent()->getGraduate())
                ->setCellValue($columns['Mode de paiement'].$i, $membership->getMethod()->getDescription())
                ->setCellValue($columns['Date d\'adhésion'].$i, $membership->getPayedOn())
            ;
            $count = 0;
            foreach ($membership->getStudent()->getPlacements() as $placement) {
                if ($placement->getRepartition()->getPeriod()->getEnd() < new \DateTime('now')) {
                    $count++;
                    foreach ($placement->getRepartition()->getDepartment()->getAccreditations() as $accreditation) {
                        $phpExcelObject->setActiveSheetIndex(0)
                            ->setCellValue($columns[$accreditation->getSector()->getName()].$i, 'oui')
                        ;
                    }
                }
            }
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('R'.$i, $count);
            foreach ($memberinfos[$membership->getId()] as $question => $info) {
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue($columns[$question].$i, $info);
            }
            $i++;
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

    /**
     * Add Filter action
     *
     * @Route("/admin/membership/filter/add/{type}/{id}/{value}", name="GRegister_AAddFilter")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function addFilterAction($type, $id, $value)
    {
        $membership_filters = $this->session->get('gregister_membership_filter', array(
            'valid'     => null,
            'questions' => null,
        ));

        if ($type == "valid") {
            $membership_filters['valid'] = $value;
        } else {
            $membership_filters[$type][$id] = $value;
        }

        $this->session->set('gregister_membership_filter', $membership_filters);

        return $this->redirect($this->generateUrl('GRegister_AIndex'));
    }

    /**
     * Remove Filter action
     *
     * @Route("/admin/membershipfilter/remove/{type}/{id}", name="GRegister_ARemoveFilter")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function removeFilterAction($type, $id)
    {
        $membership_filters = $this->session->get('gregister_membership_filter', array(
            'valid'     => null,
            'questions' => null,
        ));

        if ($type == "valid") {
            $membership_filters['valid'] = null;
        } else {
            if ($membership_filters[$type][$id] != null) {
                unset($membership_filters[$type][$id]);
            }
        }

        $this->session->set('gregister_membership_filter', $membership_filters);

        return $this->redirect($this->generateUrl('GRegister_AIndex'));
    }
}
