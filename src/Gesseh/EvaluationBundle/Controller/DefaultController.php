<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\EvaluationBundle\Entity\Evaluation;
use Gesseh\EvaluationBundle\Entity\EvalSector;
use Gesseh\EvaluationBundle\Form\EvaluationType;
use Gesseh\EvaluationBundle\Form\EvaluationHandler;

/**
 * EvaluationBundle DefaultController
 *
 * @Route("/evaluation")
 */
class DefaultController extends Controller
{
    /**
     * Affiche les évaluations d'un terrain de stage
     *
     * @Route("/department/{id}", name="GEval_DShow", requirements={"id" = "\d+"})
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $pm = $this->container->get('kdb_parameters.manager');
        $um = $this->container->get('fos_user.user_manager');
        $username = $this->get('security.token_storage')->getToken()->getUsername();

        /* Vérification des droits ROLE_STUDENT sinon sélection uniquement des EvalCriteria où isPrivate == false */
        $user = $um->findUserByUsername($username);
        if ($user->hasRole('ROLE_STUDENT') or $user->hasRole('ROLE_ADMIN')) {
            $limit['role'] = false;

            /* Vérification de l'évaluation de tous ses stages (sauf le courant) par l'étudiant */
            $student = $em->getRepository('GessehUserBundle:Student')->getByUsername($username);
            $current_period = $em->getRepository('GessehCoreBundle:Period')->getCurrent();
            $count_placements = $em->getRepository('GessehCoreBundle:Placement')->getCountByStudentWithoutCurrentPeriod($student, $current_period);
            if ($pm->findParamByName('eval_block_unevaluated')->getValue() and $em->getRepository('GessehEvaluationBundle:Evaluation')->studentHasNonEvaluated($student, $current_period, $count_placements)) {
                $this->get('session')->getFlashBag()->add('error', 'Il y a des évaluations non réalisées. Veuillez évaluer tous vos stages avant de pouvoir accéder aux autres évaluations.');
                return $this->redirect($this->generateUrl('GCore_PIndex'));
            }

            /* Vérification de l'adhésion de l'étudiant */
            if ($pm->findParamByName('eval_block_nonmember')->getValue() and !$em->getRepository('GessehRegisterBundle:Membership')->getCurrentForStudent($student, true)) {
                $this->get('session')->getFlashBag()->add('error', 'Il faut être à jour de ses cotisations pour pouvoir accéder aux évaluations.');
                return $this->redirect($this->generateUrl('GRegister_UIndex'));
            }
        } else {
            $limit['role'] = true;

            if ($user->hasRole('ROLE_SUPERTEACHER') or $em->getRepository('GessehCoreBundle:Accreditation')->getByDepartmentAndUser($id, $user->getId())) {
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Vous n\'avez pas les droits suffisants pour accéder aux évaluations d\'autres terrain de stage.');
                return $this->redirect($this->generateUrl('GCore_FSIndex'));
            }
        }

        $limit['date'] = date('Y-m-d H:i:s', strtotime('-' . $pm->findParamByName('eval_limit')->getValue() . ' year'));
        $department = $em->getRepository('GessehCoreBundle:Department')->find($id);
        if (!$department)
            throw $this->createNotFoundException('Unable to find department entity.');

        $eval = $em->getRepository('GessehEvaluationBundle:Evaluation')->getByDepartment($id, $limit);
        $count_eval = $em->getRepository('GessehEvaluationBundle:Evaluation')->countByDepartment($id, $limit);
        if (!$user->hasRole('ROLE_STUDENT') and $count_eval < $pm->findParamByName('eval_block_min')->getValue()) {
            $eval = null;
        }

        return array(
            'department' => $department,
            'eval'       => $eval,
        );
    }

    /**
     * Evaluer un stage
     *
     * @Route("/placement/{id}", name="GEval_DEval", requirements={"id" = "\d+"})
     * @Template()
     * @Security("has_role('ROLE_STUDENT')")
     */
    public function evaluateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $pm = $this->container->get('kdb_parameters.manager');
        $placement = $em->getRepository('GessehCoreBundle:Placement')->find($id);

        if (!$placement)
            throw $this->createNotFoundException('Unable to find placement entity.');

        $eval_forms = array();
        $accreditations = $em->getRepository('GessehCoreBundle:Accreditation')->getByDepartmentAndPeriod($placement->getRepartition()->getDepartment()->getId(), $placement->getRepartition()->getPeriod());
        foreach ($accreditations as $accreditation) {
            if($eval_sector = $em->getRepository('GessehEvaluationBundle:EvalSector')->getEvalSector($accreditation->getSector()->getId()))
                $eval_forms[] = $eval_sector->getForm();
        }

        if (null != $eval_forms) {
            $form = $this->createForm(new EvaluationType($eval_forms));
            $form_handler = new EvaluationHandler($form, $this->get('request'), $em, $placement, $eval_forms, $pm->findParamByName('eval_moderate')->getValue());

            if ($form_handler->process()) {
                $this->get('session')->getFlashBag()->add('notice', 'Évaluation du stage "' . $placement->getRepartition()->getDepartment()->getName() . ' à ' . $placement->getRepartition()->getDepartment()->getHospital()->getName() . '" enregistrée.');

                return $this->redirect($this->generateUrl('GCore_PIndex'));
            }

            return array(
                'placement' => $placement,
                'form'      => $form->createView(),
            );
        } else {
            return array(
                'placement' => $placement,
                'form'      => null,
            );
        }
    }

    /**
     * Affiche l'évaluation d'un étudiant
     *
     * @Route("/placement/{id}/show", name="GEval_DShowStudent", requirements={"id" = "\d+"})
     * @Template()
     * @Security("has_role('ROLE_SUPERTEACHER')")
     */
    public function showStudentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $pm = $this->container->get('kdb_parameters.manager');
        $limit['role'] = true;
        $evals = $em->getRepository('GessehEvaluationBundle:Evaluation')->getByPlacement($id, $limit);

        return array(
            'evals' => $evals,
        );
    }
}
