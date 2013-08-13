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
 * @Route("/admin/e")
 */
class AdminController extends Controller
{
  /**
   * @Route("/", name="GEval_AIndex")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $eval_forms = $em->getRepository('GessehEvaluationBundle:EvalForm')->findAll();
    $sectors = $em->getRepository('GessehEvaluationBundle:EvalSector')->findAll();

    return array(
      'eval_forms'     => $eval_forms,
      'eval_form_id'   => null,
      'eval_form_form' => null,
      'sectors'        => $sectors,
      'sector_id'      => null,
      'sector_form'    => null,
    );
  }

  /**
   * Displays a form to create a new eval_form entity.
   *
   * @Route("/new", name="GEval_ANew")
   * @Template("GessehEvaluationBundle:Admin:index.html.twig")
   */
  public function newAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $eval_forms = $em->getRepository('GessehEvaluationBundle:EvalForm')->findAll();
    $sectors = $em->getRepository('GessehEvaluationBundle:EvalSector')->findAll();

    $eval_form = new EvalForm();
    $form = $this->createForm(new EvalFormType(), $eval_form);
    $formHandler = new EvalFormHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Formulaire d\'évaluation "' . $eval_form->getName() . '" enregistré.');
      return $this->redirect($this->generateUrl('GEval_AIndex'));
    }

    return array(
      'eval_forms'     => $eval_forms,
      'eval_form_id'   => null,
      'eval_form_form' => $form->createView(),
      'sectors'        => $sectors,
      'sector_id'      => null,
      'sector_form'    => null,
    );
  }

  /**
   * Displays a form to edit an existing eval_form entity.
   *
   * @Route("/{id}/e", name="GEval_AEdit", requirements={"id" = "\d+"})
   * @Template("GessehEvaluationBundle:Admin:index.html.twig")
   */
  public function editAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $eval_forms = $em->getRepository('GessehEvaluationBundle:EvalForm')->findAll();
    $sectors = $em->getRepository('GessehEvaluationBundle:EvalSector')->findAll();

    $eval_form = $em->getRepository('GessehEvaluationBundle:EvalForm')->find($id);

    if (!$eval_form)
      throw $this->createNotFoundException('Unable to find eval_form entity.');

    $editForm = $this->createForm(new EvalFormType(), $eval_form);
    $formHandler = new EvalFormHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Formulaire d\'évaluation "' . $eval_form->getName() . '" modifié.');
      return $this->redirect($this->generateUrl('GEval_AIndex'));
    }

    return array(
      'eval_forms'     => $eval_forms,
      'eval_form_id'   => $id,
      'eval_form_form' => $editForm->createView(),
      'sectors'        => $sectors,
      'sector_id'      => null,
      'sector_form'    => null,
    );
  }

  /**
   * Deletes a eval_form entity.
   *
   * @Route("/{id}/d", name="GEval_ADelete", requirements={"id" = "\d+"}))
   */
  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
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
   * @Route("/c/{id}/d", name="GEval_ADeleteCriteria", requirements={"id" = "\d+"}))
   */
  public function deleteCriteriaAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $criteria = $em->getRepository('GessehEvaluationBundle:EvalCriteria')->find($id);

    if (!$criteria)
      throw $this->createNotFoundException('Unable to find eval_criteria entity.');

    $em->remove($criteria);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Critère d\'évaluation "' . $criteria->getName() . '" supprimé.');
    return $this->redirect($this->generateUrl('GEval_AIndex'));
  }

  /**
   * Display a form to create a new eval_sector entity
   *
   * @Route("/es/new", name="GEval_ASectorNew")
   * @Template("GessehEvaluationBundle:Admin:index.html.twig")
   */
  public function newSectorAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $eval_forms = $em->getRepository('GessehEvaluationBundle:EvalForm')->findAll();
    $sectors = $em->getRepository('GessehEvaluationBundle:EvalSector')->findAll();
    $exclude_sectors = array();

    foreach($sectors as $sector) {
      $exclude_sectors[] = $sector->getSector()->getId();
    }

    $eval_sector = new EvalSector();
    $form = $this->createForm(new EvalSectorType($exclude_sectors), $eval_sector);
    $formHandler = new EvalSectorHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Relation "' . $eval_sector->getSector() . " : " . $eval_sector->getForm() . '" enregistrée.');
      return $this->redirect($this->generateUrl('GEval_AIndex'));
    }

    return array(
      'eval_forms'     => $eval_forms,
      'eval_form_id'   => null,
      'eval_form_form' => null,
      'sectors'        => $sectors,
      'sector_id'      => null,
      'sector_form'    => $form->createView(),
    );
  }

  /**
   * Display a form to edit an eval_sector entity
   *
   * @Route("/es/{id}/e", name="GEval_ASectorEdit", requirements={"id" = "\d+"})
   * @Template("GessehEvaluationBundle:Admin:index.html.twig")
   */
/*  public function editSectorAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $eval_forms = $em->getRepository('GessehEvaluationBundle:EvalForm')->findAll();
    $sectors = $em->getRepository('GessehEvaluationBundle:EvalSector')->findAll();

    $eval_sector = $em->getRepository('GessehEvaluationBundle:EvalSector')->find($id);

    if (!$eval_sector)
      throw $this->createNotFoundException('Unable to find eval_sector entity.');

    $editForm = $this->createForm(new EvalSectorType(), $eval_sector);
    $formHandler = new EvalSectorHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Relation "' . $eval_sector->getSector() . " : " . $eval_sector->getForm() . '" modifiée.');
      return $this->redirect($this->generateUrl('GEval_AIndex'));
    }

    return array(
      'eval_forms'     => $eval_forms,
      'eval_form_id'   => null,
      'eval_form_form' => null,
      'sectors'        => $sectors,
      'sector_id'      => $id,
      'sector_form'    => $editForm->createView(),
    );
  } */

  /**
   * Deletes a eval_sector entity.
   *
   * @Route("/es/{id}/d", name="GEval_ASectorDelete", requirements={"id" = "\d+"}))
   */
  public function deleteSectorAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
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
    $em = $this->getDoctrine()->getEntityManager();
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
   * @Route("/t/{id}/d", name="GEval_ATextDelete", requirements={"id" = "\d+"})
   */
  public function textDeleteAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
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
   * @Route("/t/{id}/e", name="GEval_ATextEdit", requirements={"id" = "\d+"})
   * @Template()
   */
  public function textEditAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
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
        $em = $this->getDoctrine()->getEntityManager();
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
                'ignore-load-errors' => true,
            )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Evaluations.pdf"'
            )
        );
    }
}
