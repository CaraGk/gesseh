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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Gesseh\EvaluationBundle\Entity\EvalForm;
use Gesseh\EvaluationBundle\Form\EvalFormType;
use Gesseh\EvaluationBundle\Form\EvalFormHandler;
use Gesseh\EvaluationBundle\Entity\EvalSector;
use Gesseh\EvaluationBundle\Form\EvalSectorType;
use Gesseh\EvaluationBundle\Form\EvalSectorHandler;

/**
 * Admin controller.
 *
 * @Route("/admin/evaluation")
 */
class AdminController extends Controller
{
  /**
   * @Route("/", name="GEval_AIndex")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $eval_forms = $em->getRepository('GessehEvaluationBundle:EvalForm')->findAll();
    $sectors = $em->getRepository('GessehEvaluationBundle:EvalSector')->getAllByForm($eval_forms);

    return array(
      'eval_forms'     => $eval_forms,
      'eval_form_id'   => null,
      'eval_form_form' => null,
      'sectors'        => $sectors,
      'sector_form'    => null,
      'form_id'        => null,
    );
  }

  /**
   * Displays a form to create a new eval_form entity.
   *
   * @Route("/new", name="GEval_ANew")
   * @Template("GessehEvaluationBundle:Admin:form.html.twig")
   */
  public function newFormAction()
  {
    $em = $this->getDoctrine()->getManager();

    $eval_form = new EvalForm();
    $form = $this->createForm(new EvalFormType(), $eval_form);
    $formHandler = new EvalFormHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Formulaire d\'évaluation "' . $eval_form->getName() . '" enregistré.');
      return $this->redirect($this->generateUrl('GEval_AIndex'));
    }

    return array(
      'form' => $form->createView(),
    );
  }

  /**
   * Displays a form to edit an existing eval_form entity.
   *
   * @Route("/{id}/edit", name="GEval_AEdit", requirements={"id" = "\d+"})
   * @Template("GessehEvaluationBundle:Admin:form.html.twig")
   */
  public function editFormAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $eval_form = $em->getRepository('GessehEvaluationBundle:EvalForm')->find($id);

    if (!$eval_form)
      throw $this->createNotFoundException('Unable to find eval_form entity.');

    $form = $this->createForm(new EvalFormType(), $eval_form);
    $formHandler = new EvalFormHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Formulaire d\'évaluation "' . $eval_form->getName() . '" modifié.');
      return $this->redirect($this->generateUrl('GEval_AIndex'));
    }

    return array(
      'form' => $form->createView(),
    );
  }

  /**
   * Deletes a eval_form entity.
   *
   * @Route("/{id}/delete", name="GEval_ADelete", requirements={"id" = "\d+"}))
   */
  public function deleteFormAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $eval_form = $em->getRepository('GessehEvaluationBundle:EvalForm')->find($id);

    if (!$eval_form)
      throw $this->createNotFoundException('Unable to find eval_form entity.');

    foreach($eval_form->getCriterias() as $criteria) {
      if($evaluations = $em->getRepository('GessehEvaluationBundle:Evaluation')->findByEvalCriteria($criteria->getId())) {
        foreach($evaluations as $evaluation) {
          $em->remove($evaluation);
        }
      }
    }

    $em->remove($eval_form);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Formulaire d\'évaluation "' . $eval_form->getName() . '" supprimé.');
    return $this->redirect($this->generateUrl('GEval_AIndex'));
  }

  /**
   * Deletes a eval_criteria entity.
   *
   * @Route("/criteria/{id}/delete", name="GEval_ADeleteCriteria", requirements={"id" = "\d+"}))
   */
  public function deleteCriteriaAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $criteria = $em->getRepository('GessehEvaluationBundle:EvalCriteria')->find($id);

    if (!$criteria)
      throw $this->createNotFoundException('Unable to find eval_criteria entity.');

    $em->remove($criteria);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Critère d\'évaluation "' . $criteria->getName() . '" supprimé.');
    return $this->redirect($this->generateUrl('GEval_AIndex'));
  }

  /**
   * Display a form to add a sector to an eval_form entity
   *
   * @Route("/{form_id}/sector/add", name="GEval_ASectorAdd", requirements={"form_id" = "\d+"})
   * @Template("GessehEvaluationBundle:Admin:index.html.twig")
   */
  public function addSectorAction($form_id)
  {
    $em = $this->getDoctrine()->getManager();
    $eval_forms = $em->getRepository('GessehEvaluationBundle:EvalForm')->findAll();
    $sectors = $em->getRepository('GessehEvaluationBundle:EvalSector')->getAllByForm($eval_forms);
    $exclude_sectors = $em->getRepository('GessehEvaluationBundle:EvalSector')->getAssignedSectors();
    $eval_form = $em->getRepository('GessehEvaluationBundle:EvalForm')->find($form_id);

    $eval_sector = new EvalSector();
    $form = $this->createForm(new EvalSectorType($exclude_sectors, $eval_form), $eval_sector);
    $formHandler = new EvalSectorHandler($form, $this->get('request'), $em, $eval_form);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Relation "' . $eval_sector->getSector() . " : " . $eval_sector->getForm() . '" enregistrée.');
      return $this->redirect($this->generateUrl('GEval_AIndex'));
    }

    return array(
      'eval_forms'     => $eval_forms,
      'eval_form_id'   => null,
      'eval_form_form' => null,
      'sectors'        => $sectors,
      'sector_form'    => $form->createView(),
      'form_id'        => $form_id,
    );
  }

  /**
   * Deletes a eval_sector entity.
   *
   * @Route("/sector/{id}/delete", name="GEval_ASectorDelete", requirements={"id" = "\d+"}))
   */
  public function deleteSectorAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $eval_sector = $em->getRepository('GessehEvaluationBundle:EvalSector')->find($id);

    if (!$eval_sector)
      throw $this->createNotFoundException('Unable to find eval_sector entity.');

    $em->remove($eval_sector);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Relation "' . $eval_sector->getSector() . " : " . $eval_sector->getForm() . '" supprimée.');
    return $this->redirect($this->generateUrl('GEval_AIndex'));
  }

  /**
   * Affiche les evaluations textuelles pour modération
   *
   * @Route("/t", name="GEval_ATextIndex")
   * @Template()
   */
  public function textIndexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $paginator = $this->get('knp_paginator');
    $evaluation_query = $em->getRepository('GessehEvaluationBundle:Evaluation')->getAllText();
    $evaluations = $paginator->paginate($evaluation_query, $this->get('request')->query->get('page', 1), 20);

    return array(
      'evaluations' => $evaluations,
    );
  }

  /**
   * Supprime une évaluation textuelle
   *
   * @Route("/moderation/{id}/delete", name="GEval_ATextDelete", requirements={"id" = "\d+"})
   */
  public function textDeleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $evaluation = $em->getRepository('GessehEvaluationBundle:Evaluation')->find($id);

    if (!$evaluation)
      throw $this->createNotFoundException('Unable to find evaluation entity.');

    $em->remove($evaluation);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Évaluation textuelle supprimée.');
    return $this->redirect($this->generateUrl('GEval_ATextIndex'));
  }

  /**
   * Modifie une évaluation textuelle
   *
   * @Route("/moderation/{id}/edit", name="GEval_ATextEdit", requirements={"id" = "\d+"})
   * @Template()
   */
  public function textEditAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $paginator = $this->get('knp_paginator');
    $evaluation_query = $em->getRepository('GessehEvaluationBundle:Evaluation')->getAllText();
    $evaluations = $paginator->paginate($evaluation_query, $this->get('request')->query->get('page', 1), 20);

    return array(
      'evaluations' => $evaluations,
    );
  }
    /**
     * Exporte les évaluations en PDF
     *
     * @Route("/export", name="GEval_APdfExport")
     */
    public function pdfExportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $departments = $em->getRepository('GessehCoreBundle:Department')->getAll();

        foreach($departments as $department) {
            $eval[$department->getId()]['text'] = $em->getRepository('GessehEvaluationBundle:Evaluation')->getTextByDepartment($department->getId());
            $eval[$department->getId()]['num'] = $em->getRepository('GessehEvaluationBundle:Evaluation')->getNumByDepartment($department->getId());
            $eval[$department->getId()]['form'] = $em->getRepository('GessehEvaluationBundle:EvalSector')->getEvalSector($department->getSector()->getId());
        }

        $content = $this->renderView('GessehEvaluationBundle:Admin:pdfExport.html.twig', array(
            'eval'        => $eval,
            'departments' => $departments,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($content, array(
            )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Evaluations.pdf"'
            )
        );
    }
}
