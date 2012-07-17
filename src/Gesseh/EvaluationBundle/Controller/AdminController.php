<?php

namespace Gesseh\EvaluationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\EvaluationBundle\Entity\EvalForm;
use Gesseh\EvaluationBundle\Form\EvalFormType;
use Gesseh\EvaluationBundle\Form\EvalFormHandler;

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
      $this->get('session')->setFlash('notice', 'Formulaire d\'évaluation "' . $eval_form->getName() . '" enregistré.');
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
      $this->get('session')->setFlash('notice', 'Formulaire d\'évaluation "' . $eval_form->getName() . '" modifié.');
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

    $em->remove($eval_form);
    $em->flush();

    $this->get('session')->setFlash('notice', 'Formulaire d\'évaluation "' . $eval_form->getName() . '" supprimé.');
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
   * Display a form to edit an eval_sector entity
   *
   * @Route("/es/{id}/e", name="GEval_ASectorEdit")
   * @Template("GessehEvaluationBundle:Admin:index.html.twig")
   */
  public function editSectorAction()
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
}
