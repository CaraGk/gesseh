<?php

namespace Gesseh\EvaluationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\EvaluationBundle\Entity\Evaluation;
use Gesseh\EvaluationBundle\Entity\EvalSector;
use Gesseh\EvaluationBundle\Form\EvaluationType;
use Gesseh\EvaluationBundle\Form\EvaluationHandler;

class DefaultController extends Controller
{
    /**
     * @Route("/u/e/{id}/show", name="GEval_DShow", requirements={"id" = "\d+"})
     * @Template()
     */
    public function showAction($id)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $department = $em->getRepository('GessehCoreBundle:Department')->find($id);

      if (!$department)
        throw $this->createNotFoundException('Unable to find department entity.');

      $eval_text = $em->getRepository('GessehEvaluationBundle:Evaluation')->getTextByDepartment($id);
      $eval_num = $em->getRepository('GessehEvaluationBundle:Evaluation')->getNumByDepartment($id);
      $eval_form = $em->getRepository('GessehEvaluationBundle:EvalSector')->getEvalSector($department->getSector()->getId())->getForm();

      return array(
        'eval_text'  => $eval_text,
        'eval_num'   => $eval_num,
        'department' => $department,
        'eval_form'  => $eval_form,
      );
    }

    /**
     * @Route("/u/e/{id}/eval", name="GEval_DEval", requirements={"id" = "\d+"})
     * @Template()
     */
    public function evaluateAction($id)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $placement = $em->getRepository('GessehCoreBundle:Placement')->find($id);

      if (!$placement)
        throw $this->createNotFoundException('Unable to find placement entity.');

      $eval_form = $em->getRepository('GessehEvaluationBundle:EvalSector')->getEvalSector($placement->getDepartment()->getSector()->getId())->getForm();

      if (!$eval_form)
        throw $this->createNotFoundException('Aucun formulaire d\'évaluation associé au stage.');

      $form = $this->createForm(new EvaluationType($eval_form->getCriterias()));
      $form_handler = new EvaluationHandler($form, $this->get('request'), $em, $placement, $eval_form->getCriterias());

      if ($form_handler->process()) {
        $this->get('session')->setFlash('notice', 'Évaluation du stage "' . $placement->getDepartment()->getName() . ' à ' . $placement->getDepartment()->getHospital()->getName() . '" enregistrée.');
        return $this->redirect($this->generateUrl('GCore_PIndex'));
      }

      return array(
        'placement' => $placement,
        'form'      => $form->createView(),
        'eval_form' => $eval_form,
      );
    }
}
