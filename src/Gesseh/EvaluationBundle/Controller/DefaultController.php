<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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

        /* Vérification de l'évaluation de tous ses stages (sauf le courant) par l'étudiant */
        $student = $em->getRepository('GessehUserBundle:Student')->getByUsername($this->get('security.context')->getToken()->getUsername());
        $current_period = $em->getRepository('GessehCoreBundle:Period')->getCurrent();
        $count_placements = $em->getRepository('GessehCoreBundle:Placement')->getCountByStudentWithoutCurrentPeriod($student, $current_period);
        if ($em->getRepository('GessehEvaluationBundle:Evaluation')->studentHasNonEvaluated($student, $current_period, $count_placements)) {
            $this->get('session')->getFlashBag()->add('error', 'Il y a des évaluations non réalisées. Veuillez évaluer tous vos stages avant de pouvoir accéder aux autres évaluations.');
            return $this->redirect($this->generateUrl('GCore_PIndex'));
        }

        $eval_limit = date('Y-m-d H:i:s', strtotime('-' . $pm->findParamByName('eval_limit')->getValue() . ' year'));
        $department = $em->getRepository('GessehCoreBundle:Department')->find($id);
        if (!$department)
            throw $this->createNotFoundException('Unable to find department entity.');

        $eval_text = $em->getRepository('GessehEvaluationBundle:Evaluation')->getTextByDepartment($id, $eval_limit);
        $eval_num = $em->getRepository('GessehEvaluationBundle:Evaluation')->getNumByDepartment($id, $eval_limit);
        $eval_mult = $em->getRepository('GessehEvaluationBundle:Evaluation')->getMultByDepartment($id, $eval_limit);

        if ($eval_sector = $em->getRepository('GessehEvaluationBundle:EvalSector')->getEvalSector($department->getSector()->getId()))
            $eval_form = $eval_sector->getForm();
        else
            throw $this->createNotFoundException('Aucun formulaire d\'évaluation attribué à ce stage ! Veuillez contacter un administrateur.');

        return array(
            'eval_text'  => $eval_text,
            'eval_num'   => $eval_num,
            'department' => $department,
            'eval_form'  => $eval_form,
            'eval_mult'  => $eval_mult,
        );
    }

    /**
     * Evaluer un stage
     *
     * @Route("/placement/{id}", name="GEval_DEval", requirements={"id" = "\d+"})
     * @Template()
     */
    public function evaluateAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $pm = $this->container->get('kdb_parameters.manager');
      $placement = $em->getRepository('GessehCoreBundle:Placement')->find($id);

      if (!$placement)
        throw $this->createNotFoundException('Unable to find placement entity.');

      $eval_sector = $em->getRepository('GessehEvaluationBundle:EvalSector')->getEvalSector($placement->getDepartment()->getSector()->getId());

      if (null !== $eval_sector) {
        $eval_form = $eval_sector->getForm();
        $form = $this->createForm(new EvaluationType($eval_form->getCriterias()));
        $form_handler = new EvaluationHandler($form, $this->get('request'), $em, $placement, $eval_form->getCriterias(), $pm->findParamByName('eval_moderate')->getValue());
        if ($form_handler->process()) {
          $this->get('session')->getFlashBag()->add('notice', 'Évaluation du stage "' . $placement->getDepartment()->getName() . ' à ' . $placement->getDepartment()->getHospital()->getName() . '" enregistrée.');

          return $this->redirect($this->generateUrl('GCore_PIndex'));
        }

        return array(
          'placement' => $placement,
          'form'      => $form->createView(),
          'eval_form' => $eval_form,
        );
      } else {
        return array(
          'placement' => $placement,
          'form'      => null,
          'eval_form' => null,
        );
      }
    }
}
